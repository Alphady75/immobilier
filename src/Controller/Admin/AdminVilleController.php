<?php

namespace App\Controller\Admin;

use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/ville')]
class AdminVilleController extends AbstractController
{
    public function __construct(SluggerInterface $sluger)
    {
        $this->sluger = $sluger;
    }

    #[Route('/', name: 'admin_ville_index', methods: ['GET'])]
    public function index(VilleRepository $villeRepository): Response
    {
        return $this->render('admin/admin_ville/index.html.twig', [
            'villes' => $villeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_ville_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ville = new Ville();
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $ville->setSlug(strtolower($this->sluger->slug($ville->getName())));

            $entityManager->persist($ville);
            $entityManager->flush();

            $ville->setSlug($ville->getSlug() . '-' . $ville->getId());

            $entityManager->flush();

            $this->addFlash('success', 'Le contenu à bien été enregistrer');

            return $this->redirectToRoute('admin_ville_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_ville/new.html.twig', [
            'ville' => $ville,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_ville_show', methods: ['GET'])]
    public function show(Ville $ville): Response
    {
        return $this->render('admin/admin_ville/show.html.twig', [
            'ville' => $ville,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_ville_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ville $ville, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $ville->setSlug(strtolower($this->sluger->slug($form->get('name')->getData())));
            $entityManager->flush();

            $ville->setSlug($ville->getSlug() . '-' . $ville->getId());

            $entityManager->flush();

            $this->addFlash('success', 'Le contenu à bien été modifier');

            return $this->redirectToRoute('admin_ville_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_ville/edit.html.twig', [
            'ville' => $ville,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_ville_delete', methods: ['POST'])]
    public function delete(Request $request, Ville $ville, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ville->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ville);
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été supprimer');
        }

        return $this->redirectToRoute('admin_ville_index', [], Response::HTTP_SEE_OTHER);
    }
}
