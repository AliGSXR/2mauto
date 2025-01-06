<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $name = $request->request->get('name');
            $email = $request->request->get('email');
            $subject = $request->request->get('subject');
            $message = $request->request->get('message');

            try {
                // Créer l'e-mail
                $emailMessage = (new Email())
                    ->from($email) // L'adresse e-mail du visiteur
                    ->to('ali.test2025beta@gmail.com') // Votre adresse e-mail
                    ->subject($subject)
                    ->text(
                        "Nom : $name\n" .
                        "E-mail : $email\n\n" .
                        "Message :\n$message"
                    );

                // Envoyer l'e-mail
                $mailer->send($emailMessage);

                // Ajouter un message flash de succès
                $this->addFlash('success', 'Votre message a été envoyé avec succès !');
            } catch (\Exception $e) {
                // Ajouter un message flash en cas d'erreur
                $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer plus tard.');
            }

            // Redirection vers la page contact
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig');
    }
}