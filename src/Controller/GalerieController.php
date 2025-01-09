<?php

namespace App\Controller;

use App\Entity\GalerieImage;
use App\Entity\GalerieSection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Stopwatch\Section;

class GalerieController extends AbstractController
{
    #[Route('/galerie', name: 'app_galerie')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $sections = $entityManager->getRepository(GalerieSection::class)->findAll();
        $imagesBySection = [];

        foreach ($sections as $section) {
            $imagesBySection[$section->getNom()] = $entityManager->getRepository(GalerieImage::class)->findBy(
                ['section' => $section],
            ['position' => 'ASC']);
        }
        return $this->render('galerie/index.html.twig', [
            'imagesBySection' => $imagesBySection,
            'controller_name' => 'GalerieController',
        ]);
    }
}
