<?php

namespace App\Controller;

use App\Entity\Immobilier;
use App\Entity\Contact;
use App\Entity\SearchImmobilier;
use App\Form\SearchImmobilierType;
use App\Form\ContactType;
use App\Service\MailerService;
use App\Repository\ImmobilierRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\CategorieImmobilierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/immobiliers')]
class ImmobiliersController extends AbstractController
{
    #[Route('/', name: 'immobiliers', methods: ['POST', 'GET'])]
    public function immobilier(
        CategorieImmobilierRepository $categorieRepo, PaginatorInterface $paginator,
        ImmobilierRepository $immobilierRepository, Request $request
    ): Response
    {
        $search = new SearchImmobilier();
        $search->page = $request->get('page', 1);

        $form = $this->createForm(SearchImmobilierType::class, $search);
        $form->handleRequest($request);

        $immobiliers = $immobilierRepository->findVisibleSearch($search);

        return $this->render('immobiliers/immobiliers.html.twig', [
            'form' => $form->createView(),
            'immobiliers' => $immobiliers,
            'min' => 0,
            'max' => 1000000,
            'current' => $search->page,
        ]);
    }

    #[Route('/{slug}', name: 'immobiliers_details', methods: ['GET', 'POST'])]
    public function details(
        Immobilier $immobilier, Request $request, 
        EntityManagerInterface $entityManager,
        MailerService $mailer, ImmobilierRepository $immobilierRepository
    ): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contact->setImmobilier($immobilier);
            $contact->setLu(false);

            // Envoie de mail
            $username = $form->get('civilite')->getData() . ' ' . $form->get('nom')->getData() . ' ' . $form->get('prenom')->getData();

            $from = $form->get('email')->getData();

            $to = $this->getParameter('app_email');

            $message = $form->get('message')->getData();

            $mailer->sendEmailForImmobilier($from, $to, $message, $username, $immobilier);

            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash('info', 'Message de contact envoyer');

            return $this->redirectToRoute('immobiliers_details', [
                'slug' => $immobilier->getSlug()
            ], Response::HTTP_SEE_OTHER);
        }

        $categorie = $immobilier->getCategorie();

        $immobilierSimilaire = $immobilierRepository->findSimilaires($categorie, $immobilier);

        return $this->render('immobiliers/details.html.twig', [
            'immobilier' => $immobilier,
            'form' => $form->createView(),
            'immobiliersSimilaires' => $immobilierSimilaire,
        ]);
    }
}
