<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer, LoggerInterface $logger): Response
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
                    ->to('2m.auto01500@gmail.com') // Votre adresse e-mail
                    ->subject($subject)
                    ->html( // Contenu HTML de l'e-mail
                        "<h2>Demande de contact</h2>
                        <p><strong>Nom :</strong> {$name}</p>
                        <p><strong>E-mail :</strong> {$email}</p>
                        <p><strong>Sujet :</strong> {$subject}</p>
                        <p><strong>Message :</strong></p>
                        <p style='background-color: #f9f9f9; padding: 15px; border: 1px solid #ddd;'>{$message}</p>
                        <hr>
                        <p style='font-size: 12px; color: #555;'>Cet e-mail a été envoyé depuis le formulaire de contact de votre site web.</p>"
                    );


                // Envoyer l'e-mail
                $mailer->send($emailMessage);

                // Log de succès
                $logger->info('Email envoyé avec succès.');

                // Ajouter un message flash de succès
                $this->addFlash('success', 'Votre message a été envoyé avec succès !');
            } catch (TransportExceptionInterface $e) {

                // Log d'erreur
                $logger->error('Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
                // Ajouter un message flash en cas d'erreur
                $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer plus tard.');
            }

            // Redirection vers la page contact

            return $this->redirectToRoute('app_contact');
        }


        return $this->render('contact/index.html.twig');
    }
}