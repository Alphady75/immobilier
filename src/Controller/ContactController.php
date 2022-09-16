<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(
        Request $request, MailerService $mailer
    ): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Envoie de mail
            $username = $form->get('civilite')->getData() . ' ' . $form->get('nom')->getData() . ' ' . $form->get('prenom')->getData();

            $from = $form->get('email')->getData();

            $to = $this->getParameter('app_email');

            $message = $form->get('message')->getData();

            $mailer->sendMail($from, $to, $username, $message);

            $this->addFlash('info', 'Message de contact envoyer');

            return $this->redirectToRoute('contact', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
