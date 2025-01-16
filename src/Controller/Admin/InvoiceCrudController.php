<?php

namespace App\Controller\Admin;

use App\Entity\Invoice;
use App\Entity\ServiceFact;
use App\Form\ServiceFactType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;

class InvoiceCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Invoice::class;

    }


    #[Route('/admin/invoice/{id}/pdf', name: 'invoice_pdf')]


    public function generatePdf(int $id, EntityManagerInterface $entityManager): Response
    {
        // Chargez l'entité Invoice à partir de l'ID
        $invoice = $entityManager->getRepository(Invoice::class)->find($id);

        if (!$invoice) {
            throw $this->createNotFoundException('Facture introuvable pour l\'ID ' . $id);
        }
        $totalHT = 0;

        foreach ($invoice->getServiceFacts() as $serviceFact) {
            $unitPrice = (float) $serviceFact->getUnitPrix();
            $quantity = (int) $serviceFact->getQuantity();
            $totalHT += $unitPrice * $quantity;
        }

        $serviceFacts = $invoice->getServiceFacts(); // Vérifiez que cette collection est remplie

        $totalHT = 0;
        $tvaRate = 0;

        // Calculer le total HT à partir des services associés
        foreach ($invoice->getServiceFacts() as $service) {
            $totalHT += $service->getUnitPrix() * $service->getQuantity();
        }
        foreach ($invoice->getServices() as $serviceOption) {
            $totalHT += $serviceOption->getPrix();
        }

        // Calculer la TVA et le total TTC
        $tva = $totalHT * $tvaRate;
        $totalTTC = $totalHT + $tva;


        // Configure DomPDF options
        $options = new Options();
        $options->setIsRemoteEnabled(true); // Autorise les URL distantes
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Render the Twig template
        $html = $this->renderView('admin/invoice/pdf.html.twig', [
            'invoice' => $invoice,
            'clientName' => $invoice->getClientName(),
            'clientAdress' => sprintf(
                '%s, %s %s',
                $invoice->getStreet() ?? 'Rue non renseignée',
                $invoice->getPostalCode() ?? 'Code postal non renseigné',
                $invoice->getCity() ?? 'Ville non renseignée'
            ),
            'services' => $invoice->getServices(),
            'details' => $invoice->getDetails(),
            'totalHT' => $totalHT,
            'tva' => $tva,
            'totalTTC' => $totalTTC,
            'supplements' => $invoice->getServices(),
        ]);

        // Load HTML into DomPDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $fileName = 'facture-' . $invoice->getId() . '.pdf';


        // Return the PDF as a Response
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="facture-' . $invoice->getId() . '.pdf"',
        ]);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Invoice) {
            $client = $entityInstance->getClient();
            if ($client) {
                $entityInstance->setClientName($client->getClientName());
                $entityInstance->setStreet($client->getStreet());
                $entityInstance->setPostalCode($client->getPostalCode());
                $entityInstance->setCity($client->getCity());
            }
        }
        $entityInstance->setTotalHTC($entityInstance->calculateHTC());
        $entityInstance->setTva($entityInstance->calculateTva(20)); // 20% de TVA
        $entityInstance->setTotalTTC($entityInstance->calculateTotalTTC(20));

        if ($entityInstance instanceof Invoice) {
            foreach ($entityInstance->getServiceFacts() as $serviceFact) {
                if (!$serviceFact->getUnitPrix()) {
                    throw new \RuntimeException('Le prix unitaire est obligatoire pour chaque service.');
                }
            }
        }
        if (!$entityInstance->getDateEcheance()) {
            $entityInstance->setDateEcheance((new \DateTime())->modify('+30 days')); // Date d'échéance par défaut
        }



        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Invoice) {
            $client = $entityInstance->getClient();
            if ($client) {
                $entityInstance->setClientName($client->getClientName());
                $entityInstance->setStreet($client->getStreet());
                $entityInstance->setPostalCode($client->getPostalCode());
                $entityInstance->setCity($client->getCity());
            }

        }
        if ($entityInstance instanceof Invoice && !$entityInstance->getDateEcheance()) {
            $entityInstance->setDateEcheance((new \DateTime())->modify('+30 days'));
        }
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function configureActions(Actions $actions): Actions
    {
        $viewPdfAction = Action::new('viewPdf', 'Voir PDF', 'fas fa-file-pdf')
            ->linkToRoute('invoice_pdf', function (Invoice $invoice): array {
                return ['id' => $invoice->getId()];
            })
            ->addCssClass('btn btn-primary');

        return $actions
            ->add(Action::INDEX, $viewPdfAction)
            ->add(Action::DETAIL, $viewPdfAction);
    }
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['calculateTotals'],
            BeforeEntityUpdatedEvent::class => ['calculateTotals'],
        ];
    }

    public function calculateTotals(BeforeEntityPersistedEvent|BeforeEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof Invoice) {

            return;
        }

        $totalHTC = 0;

        // Calculez le total HT en fonction des services associés
        foreach ($entity->getServiceFacts() as $serviceFact) {
            $totalHTC += $serviceFact->getUnitPrix() * $serviceFact->getQuantity();
        }

        $tva = $totalHTC * 0.2; // Exemple : TVA 20%
        $totalTTC = $totalHTC + $tva;

        // Mettez à jour les totaux dans l'entité
        $entity->setTotalHTC($totalHTC);
        $entity->setTva($tva);
        $entity->setTotalTTC($totalTTC);
    }
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('Date', 'Date de facture')->setRequired(true),
            DateTimeField::new('dateEcheance', 'Date d\'échéance')
                ->setRequired(false)
                ->setHelp('La date d\'échéance sera calculée automatiquement à 30 jours par défaut si non spécifiée.'),
            AssociationField::new('client', 'Client')->setRequired(true)
                ->setCrudController(ClientCrudController::class),

            TextField::new('plaqueImmatriculation', 'Plaque d\'immatriculation')
                    ->setRequired(false),
            CollectionField::new('serviceFacts', 'Services associés')
                ->setEntryType(ServiceFactType::class)
                ->allowAdd(true)
                ->allowDelete(true),
            AssociationField::new('services', 'Services et Suppléments')
                ->setFormTypeOption('by_reference', false),


            TextEditorField::new('details', 'Détails ou remarques')
                ->setRequired(false),
        ];
    }
}
