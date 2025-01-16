<?php

namespace App\Controller\Admin;

use App\Entity\ServiceFact;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ServiceFactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ServiceFact::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('description', 'Description du service'),
            MoneyField::new('unitPrix', 'Prix unitaire')->setCurrency('EUR'),
            IntegerField::new('quantity','Quantité')
        ];
    }

}
