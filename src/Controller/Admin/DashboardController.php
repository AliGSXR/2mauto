<?php

namespace App\Controller\Admin;


use App\Entity\Avis;
use App\Entity\Client;
use App\Entity\GalerieImage;
use App\Entity\Invoice;
use App\Entity\ServiceFact;
use App\Entity\ServiceOption;
use App\Entity\Services;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ServicesCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('2M AUTO SERVICES');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Gestion des contenus');
        yield MenuItem::linkToCrud('Services', 'fas fa-briefcase', Services::class);
        yield MenuItem::linkToCrud('Avis', 'fa fa-star', Avis::class);
        yield MenuItem::linkToCrud('Galerie', 'fa fa-images', GalerieImage::class);

        yield MenuItem::section('Factures');
        yield MenuItem::linkToCrud('Facturation', 'fa fa-star', Invoice::class);
        yield MenuItem::linkToCrud('Services Facturation', 'fa fa-star', ServiceFact::class);
        yield MenuItem::linkToCrud('Clients', 'fa fa-star', Client::class );
        yield MenuItem::linkToCrud('Suppléments', 'fa fa-star', ServiceOption::class );

        yield MenuItem::section('Accès rapide');
        yield MenuItem::linkToUrl('Voir le site', 'fa fa-eye', '/');
    }
}
