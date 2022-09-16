<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use App\Repository\CategorieAnnonceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/annonces')]
class AnnoncesController extends AbstractController
{
    #[Route('/', name: 'annonces')]
    public function annonce(PaginatorInterface $paginator, Request $request,
        AnnonceRepository $annonceRepository, CategorieAnnonceRepository $categorieAnnonceRepo): Response
    {
        $annonces = $paginator->paginate(
            $annonceRepository->findOnlineByDateDesc(),
            $request->query->getInt('page', 1),
            8
        );

        $categories = $categorieAnnonceRepo->findByNameAsc();

        return $this->render('annonces/annonces.html.twig', [
            'annonces' => $annonces,
            'categories' => $categories,
        ]);
    }

    #[Route('/categories/{categorieslug}', name: 'annonces_categorie')]
    public function categorie(
        PaginatorInterface $paginator, Request $request,
        $categorieslug, AnnonceRepository $annonceRepository, 
        CategorieAnnonceRepository $categorieAnnonceRepo
    ): Response
    {
        $categorie = $categorieAnnonceRepo->findOneBy([
            'slug' => $categorieslug
        ]);

        $annonces = $paginator->paginate(
            $annonceRepository->findByCategories($categorie),
            $request->query->getInt('page', 1),
            8
        );

        $categories = $categorieAnnonceRepo->findByNameAsc();

        return $this->render('annonces/categories.html.twig', [
            'annonces' => $annonces,
            'categorie' => $categorie,
            'categories' => $categories,
        ]);
    }

    #[Route('/{slug}', name: 'annonces_details')]
    public function details(Annonce $annonce, CategorieAnnonceRepository $categorieAnnonceRepo): Response
    {
        $categories = $categorieAnnonceRepo->findByNameAsc();

        return $this->render('annonces/details.html.twig', [
            'annonce' => $annonce,
            'categories' => $categories,
        ]);
    }
}
