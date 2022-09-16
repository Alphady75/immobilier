<?php

namespace App\Controller\Admin;

use App\Entity\CategorieImmobilier;
use App\Form\CategorieImmobilierType;
use App\Repository\CategorieImmobilierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/categorie/immobilier')]
class AdminCategorieImmobilierController extends AbstractController
{
    public function __construct(SluggerInterface $sluger)
    {
        $this->sluger = $sluger;
    }

    #[Route('/', name: 'admin_categorie_immobilier_index', methods: ['GET'])]
    public function index(CategorieImmobilierRepository $categorieImmobilierRepository): Response
    {
        return $this->render('admin/admin_categorie_immobilier/index.html.twig', [
            'categories' => $categorieImmobilierRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_categorie_immobilier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorieImmobilier = new CategorieImmobilier();
        $form = $this->createForm(CategorieImmobilierType::class, $categorieImmobilier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categorieImmobilier->setSlug(strtolower($this->sluger->slug($categorieImmobilier->getName())));

            $entityManager->persist($categorieImmobilier);
            $entityManager->flush();

            $categorieImmobilier->setSlug($categorieImmobilier->getSlug() . '-' . $categorieImmobilier->getId());

            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été enregistrer');

            return $this->redirectToRoute('admin_categorie_immobilier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_categorie_immobilier/new.html.twig', [
            'categorie_immobilier' => $categorieImmobilier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_categorie_immobilier_show', methods: ['GET'])]
    public function show(CategorieImmobilier $categorieImmobilier): Response
    {
        return $this->render('admin/admin_categorie_immobilier/show.html.twig', [
            'categorie_immobilier' => $categorieImmobilier,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_categorie_immobilier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieImmobilier $categorieImmobilier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieImmobilierType::class, $categorieImmobilier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categorieImmobilier->setSlug(strtolower($this->sluger->slug($form->get('name')->getData())));
            $entityManager->flush();

            $categorieImmobilier->setSlug($categorieImmobilier->getSlug() . '-' . $categorieImmobilier->getId());

            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été modifer');

            return $this->redirectToRoute('admin_categorie_immobilier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_categorie_immobilier/edit.html.twig', [
            'categorie' => $categorieImmobilier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_categorie_immobilier_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieImmobilier $categorieImmobilier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieImmobilier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorieImmobilier);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Le contenu a bien été supprimer');

        return $this->redirectToRoute('admin_categorie_immobilier_index', [], Response::HTTP_SEE_OTHER);
    }
}
