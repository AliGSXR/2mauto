<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ClientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Client::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('clientName', 'Nom du client'),
            TextField::new('street', 'Rue')->setRequired(false),
            TextField::new('postalCode', 'Code postal')
                ->setRequired(false)
                ->setFormTypeOption('attr', [
                    'inputmode' => 'numeric', // Active le pavé numérique
                    'pattern' => '[0-9]*',])
                ->setHelp('Saisissez uniquement des chiffres pour le code postal.'),
            TextField::new('city', 'Ville')->setRequired(false),
            TextField::new('phone', 'Téléphone'),
        ];
    }

}
