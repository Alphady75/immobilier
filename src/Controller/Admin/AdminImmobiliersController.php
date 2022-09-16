<?php

namespace App\Controller\Admin;

use App\Entity\Immobilier;
use App\Form\ImmobilierType;
use App\Entity\ImmobilierMedia;
use App\Form\ImmobilierMediaType;
use App\Repository\ImmobilierRepository;
use App\Repository\CategorieImmobilierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/immobiliers')]
class AdminImmobiliersController extends AbstractController
{
    public function __construct(SluggerInterface $sluger)
    {
        $this->sluger = $sluger;
    }

    #[Route('/', name: 'admin_immobiliers_index', methods: ['GET'])]
    public function index(
        ImmobilierRepository $immobilierRepository,
        CategorieImmobilierRepository $categorieRepo,
        PaginatorInterface $paginator, Request $request
    ): Response
    {
        $immobiliers = $paginator->paginate(
            $immobilierRepository->findByDateDesc(),
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/admin_immobiliers/index.html.twig', [
            'immobiliers' => $immobiliers,
            'categories' => $categorieRepo->findByName(),
        ]);
    }

    #[Route('/new', name: 'admin_immobiliers_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $immobilier = new Immobilier();
        $form = $this->createForm(ImmobilierType::class, $immobilier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $immobilier->setSlug(strtolower($this->sluger->slug($immobilier->getName())));

            $immobilier->setUser($this->getUser());
            $entityManager->persist($immobilier);
            $entityManager->flush();

            $immobilier->setSlug($immobilier->getSlug() . '-' . $immobilier->getId());

            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été enregistrer');

            return $this->redirectToRoute('admin_immobiliers_show', [
                'id' => $immobilier->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_immobiliers/new.html.twig', [
            'immobilier' => $immobilier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_immobiliers_show', methods: ['GET', 'POST'])]
    public function show(Immobilier $immobilier, Request $request, EntityManagerInterface $entityManager): Response
    {
        $immobilierMedia = new ImmobilierMedia();
        $form = $this->createForm(ImmobilierMediaType::class, $immobilierMedia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $immobilierMedia->setImmobilier($immobilier);
            $immobilierMedia->setType('img');
            $entityManager->persist($immobilierMedia);
            $entityManager->flush();

            $this->addFlash('success', 'Image ajouter dans la gallerie avec succès!');

            return $this->redirectToRoute('admin_immobiliers_show', [
                'id' => $immobilier->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/admin_immobiliers/show.html.twig', [
            'immobilier' => $immobilier,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_immobiliers_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Immobilier $immobilier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ImmobilierType::class, $immobilier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $immobilier->setSlug(strtolower($this->sluger->slug($form->get('name')->getData())));
            $entityManager->flush();

            $immobilier->setSlug($immobilier->getSlug() . '-' . $immobilier->getId());

            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été modifer');

            return $this->redirectToRoute('admin_immobiliers_show', [
                'id' => $immobilier->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_immobiliers/edit.html.twig', [
            'immobilier' => $immobilier,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_immobiliers_delete', methods: ['POST'])]
    public function delete(Request $request, Immobilier $immobilier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$immobilier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($immobilier);
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été supprimer');
        }

        return $this->redirectToRoute('admin_immobiliers_index', [], Response::HTTP_SEE_OTHER);
    }
}
