<?php

namespace App\Controller\Admin;

use App\Entity\ImmobilierMedia;
use App\Form\MediaType;
use App\Repository\ImmobilierMediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/immobilier/media')]
class AdminImmobilierMediaController extends AbstractController
{
    #[Route('/', name: 'admin_immobilier_media_index', methods: ['GET'])]
    public function index(
        ImmobilierMediaRepository $immobilierMediaRepository,
        PaginatorInterface $paginator, Request $request
    ): Response
    {
        $immobiliermedias = $paginator->paginate(
            $immobilierMediaRepository->findByDateDesc(),
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/admin_immobilier_media/index.html.twig', [
            'immobilier_media' => $immobiliermedias,
        ]);
    }

    #[Route('/new', name: 'admin_immobilier_media_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $immobilierMedia = new ImmobilierMedia();
        $form = $this->createForm(MediaType::class, $immobilierMedia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($immobilierMedia);
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été enregistrer');

            return $this->redirectToRoute('admin_immobilier_media_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_immobilier_media/new.html.twig', [
            'immobilier_media' => $immobilierMedia,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_immobilier_media_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ImmobilierMedia $immobilierMedia, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MediaType::class, $immobilierMedia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été modifer');

            return $this->redirectToRoute('admin_immobiliers_show', [
                'id' => $immobilierMedia->getImmobilier()->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_immobilier_media/edit.html.twig', [
            'media' => $immobilierMedia,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_immobilier_media_delete', methods: ['POST'])]
    public function delete(Request $request, $id, EntityManagerInterface $entityManager, 
        ImmobilierMediaRepository $immobilierMediaRepository): Response
    {
        $media = $immobilierMediaRepository->find($id);

        $immobilier = $media->getImmobilier();

        if ($this->isCsrfTokenValid('delete'.$media->getId(), $request->request->get('_token'))) {
            $entityManager->remove($media);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Le contenu a bien été supprimer');

        return $this->redirectToRoute('admin_immobiliers_show', [
            'id' => $immobilier->getId()
        ], Response::HTTP_SEE_OTHER);
    }
}
