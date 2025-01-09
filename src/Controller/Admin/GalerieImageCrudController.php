<?php

namespace App\Controller\Admin;

use App\Entity\GalerieImage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GalerieImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GalerieImage::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('image', 'Image')
            ->setBasePath('/public/uploads')
            ->setUploadDir('public/uploads')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(true),

            AssociationField::new('section', 'Service')
            ->setHelp('Choisissez le service dans lequel elle se trouvera'),

            IntegerField::new('position', 'Position')
            ->setHelp('Choisissez le position de l\'image'),

        ];
    }

}
