<?php

namespace App\Controller\Admin;

use App\Entity\ServiceOption;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ServiceOptionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ServiceOption::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('name', 'Désignation'),
            TextField::new('prix', 'Prix'),
        ];
    }

}
