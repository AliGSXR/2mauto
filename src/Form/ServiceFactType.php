<?php

namespace App\Form;

use App\Entity\ServiceFact;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceFactType extends AbstractType
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager; // Injecter l'EntityManager
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', EntityType::class, [
                'class' => ServiceFact::class,
                'choice_label' => 'description',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('s');},
                'placeholder' => 'Choisissez un service',
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité',
                'attr' => ['min' => 1, 'inputmode' => 'numeric' ],

            ])
            ->add('unitPrix', MoneyType::class, [
                'label' => 'Prix unitaire',
                'currency' => 'EUR',
                'mapped' => true,
                'attr' => ['readonly' => true], // Champ non modifiable
            ]);

        // Récupération automatique du prix unitaire
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $serviceFact = $event->getData(); // Récupère l'entité liée au formulaire
            dump($serviceFact);
            $form = $event->getForm();

            if ($serviceFact && $serviceFact->getDescription()) {
                // Pré-remplit le champ "unitPrix" si un service est défini
                $form->add('unitPrix', MoneyType::class, [
                    'label' => 'Prix unitaire',
                    'currency' => 'EUR',
                    'data' => $serviceFact->getUnitPrix(), // Définit la valeur initiale
                    'mapped' => true, // Empêche la sauvegarde automatique
                ]);
            }
        });

        // Écouteur PRE_SUBMIT pour gérer les données soumises (création ou mise à jour)
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData(); // Données envoyées par le formulaire
            $form = $event->getForm();

            if (isset($data['description']) && $data['description']) {
                // Récupère l'entité ServiceFact correspondant à la description sélectionnée
                $serviceFact = $this->entityManager
                    ->getRepository(ServiceFact::class)
                    ->find($data['description']);

                if ($serviceFact) {


                    // Remplit automatiquement le champ "unitPrix"
                    $data['unitPrix'] = $serviceFact->getUnitPrix();
                    $event->setData($data); // Met à jour les données du formulaire
                }
            }
        });
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ServiceFact::class, // Lie le formulaire à l'entité ServiceFact
        ]);
    }
}