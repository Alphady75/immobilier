<?php

namespace App\Controller\Admin;

use App\Entity\CategorieAnnonce;
use App\Form\CategorieAnnonceType;
use App\Repository\CategorieAnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/categorie/annonce')]
class AdminCategorieAnnonceController extends AbstractController
{
    public function __construct(SluggerInterface $sluger)
    {
        $this->sluger = $sluger;
    }

    #[Route('/', name: 'admin_categorie_annonce_index', methods: ['GET'])]
    public function index(CategorieAnnonceRepository $categorieAnnonceRepository): Response
    {
        return $this->render('admin/admin_categorie_annonce/index.html.twig', [
            'categories' => $categorieAnnonceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_categorie_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorieAnnonce = new CategorieAnnonce();
        $form = $this->createForm(CategorieAnnonceType::class, $categorieAnnonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categorieAnnonce->setSlug(strtolower($this->sluger->slug($categorieAnnonce->getName())));

            $entityManager->persist($categorieAnnonce);
            $entityManager->flush();

            $categorieAnnonce->setSlug($categorieAnnonce->getSlug() . '-' . $categorieAnnonce->getId());

            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été enregistrer');

            return $this->redirectToRoute('admin_categorie_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_categorie_annonce/new.html.twig', [
            'categorie_annonce' => $categorieAnnonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_categorie_annonce_show', methods: ['GET'])]
    public function show(CategorieAnnonce $categorieAnnonce): Response
    {
        return $this->render('admin/admin_categorie_annonce/show.html.twig', [
            'categorie_annonce' => $categorieAnnonce,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_categorie_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieAnnonce $categorieAnnonce, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieAnnonceType::class, $categorieAnnonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categorieAnnonce->setSlug(strtolower($this->sluger->slug($form->get('name')->getData())));
            $entityManager->flush();

            $categorieAnnonce->setSlug($categorieAnnonce->getSlug() . '-' . $categorieAnnonce->getId());

            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été modifer');

            return $this->redirectToRoute('admin_categorie_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_categorie_annonce/edit.html.twig', [
            'categorie' => $categorieAnnonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_categorie_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieAnnonce $categorieAnnonce, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieAnnonce->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorieAnnonce);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Le contenu a bien été supprimer');

        return $this->redirectToRoute('admin_categorie_annonce_index', [], Response::HTTP_SEE_OTHER);
    }
}
