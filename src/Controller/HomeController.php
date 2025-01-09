<?php

namespace App\Controller;

use App\Entity\Services;
use App\Entity\Avis;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $services = $entityManager->getRepository(Services::class)->findAll();

        $avisselection = $entityManager->getRepository(Avis::class)->findOneBy(['selected' => true]);

        return $this->render('home/index.html.twig', [
            'services' => $services,
            'avisselection' => $avisselection,
            'controller_name' => 'HomeController',
        ]);


    }
}
