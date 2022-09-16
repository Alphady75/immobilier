<?php

namespace App\Controller\Admin;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/annonces')]
class AdminAnnoncesController extends AbstractController
{
    public function __construct(SluggerInterface $sluger)
    {
        $this->sluger = $sluger;
    }

    #[Route('/', name: 'admin_annonces_index', methods: ['GET'])]
    public function index(AnnonceRepository $annonceRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $annonces = $paginator->paginate(
            $annonceRepository->findByDateDesc(),
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/admin_annonces/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }

    #[Route('/new', name: 'admin_annonces_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $annonce->setSlug(strtolower($this->sluger->slug($annonce->getName())));
            $annonce->setUser($this->getUser());
            
            $entityManager->persist($annonce);
            $entityManager->flush();

            $annonce->setSlug($annonce->getSlug() . '-' . $annonce->getId());

            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été enregistrer');

            return $this->redirectToRoute('admin_annonces_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_annonces/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_annonces_show', methods: ['GET'])]
    public function show(Annonce $annonce): Response
    {
        return $this->render('admin_annonces/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_annonces_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $annonce->setSlug(strtolower($this->sluger->slug($form->get('name')->getData())));
            $entityManager->flush();

            $annonce->setSlug($annonce->getSlug() . '-' . $annonce->getId());

            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été modifer');

            return $this->redirectToRoute('admin_annonces_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_annonces/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_annonces_delete', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Le contenu a bien été supprimer');

        return $this->redirectToRoute('admin_annonces_index', [], Response::HTTP_SEE_OTHER);
    }
}
