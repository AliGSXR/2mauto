<?php

namespace App\Controller\Admin;

use App\Entity\Avis;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AvisCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Avis::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('note', 'Note')
            ->setHelp('Mettez la note entre 1 et 5')
            ->setFormTypeOption('attr', ['min' => 1, 'max' => 5]),
            TextField::new('commentaire', 'Commentaire'),
            TextField::new('auteur', 'Auteur du commentaire'),

            BooleanField::new('selected', 'Sélectionné')
            ->setHelp('Cochez pour afficher cet avis sur le site')
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Avis && $entityInstance->isSelected()){
            $otherSelected = $entityManager->getRepository(Avis::class)->findBy(['selected'=>true]);
            foreach ($otherSelected as $avis){
                if ($avis !== $entityInstance){
                    $avis->setSelected(false);
                    $entityManager->persist($avis);
                }
            }
        }
        parent::persistEntity($entityManager, $entityInstance);
    }

}
