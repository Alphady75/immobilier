<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use App\Repository\ImmobilierRepository;
use App\Repository\VilleRepository;
use App\Repository\AnnonceRepository;
use App\Repository\CategorieImmobilierRepository;
use App\Repository\CategorieAnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(
        UserRepository $userRepository, VilleRepository $villeRepository, 
        AnnonceRepository $annonceRepository, ImmobilierRepository $immobilierRepository,
        CategorieImmobilierRepository $categorieRepo, CategorieAnnonceRepository $categorieAnnonceRepo
    ): Response
    {
        $immobiliers = $immobilierRepository->findAll();
        $totalUsers = $userRepository->findAll();
        $lasteusers = $userRepository->findAll();

        //Représentation graphique des immobilier par catégorie

        $categories = $categorieRepo->findAll();

        $categorieName = [];
        $categorieColor = [];
        $categorieCount = [];

        // Démontage des données
        foreach($categories as $categorie){
            $categorieName[] = $categorie->getName();
            $categorieColor[] = $categorie->getHexColor();
            $categorieCount[] = count($categorie->getImmobiliers());
        }

        //Représentation graphique des immobilier par ville
        $villes = $villeRepository->findAll();

        $villeName = [];
        $villeColor = [];
        $villeCount = [];

        // Démontage des données
        foreach($villes as $ville){
            $villeName[] = $ville->getName();
            $villeColor[] = $ville->getHexColor();
            $villeCount[] = count($ville->getImmobiliers());
        }

        //Représentation graphique des annonces par catégorie
        $annonces = $annonceRepository->findAll();
        $categorieAnnonces = $categorieAnnonceRepo->findAll();

        $categorieAnnonceName = [];
        $categorieAnnonceColor = [];
        $categorieAnnonceCount = [];

        // Démontage des données
        foreach($categorieAnnonces as $categorieAnnonce){
            $categorieAnnonceName[] = $categorieAnnonce->getName();
            $categorieAnnonceColor[] = $categorieAnnonce->getHexColor();
            $categorieAnnonceCount[] = count($categorieAnnonce->getAnnonces());
        }

        return $this->render('admin/dashboard/dashboard.html.twig', [
            'annonces' => $annonces,
            'immobiliers' => $immobiliers,
            'villes' => $villes,
            'totalUsers' => $totalUsers,
            'users' => $userRepository->findLasted(),

            'categorieNames' =>  json_encode($categorieName),
            'categorieColors' => json_encode($categorieColor),
            'categorieCount'    =>  json_encode($categorieCount),

            'villeNames' =>  json_encode($villeName),
            'villeColors' => json_encode($villeColor),
            'villeCount'    =>  json_encode($villeCount),

            'CategorieAnnonceNames' =>  json_encode($categorieAnnonceName),
            'CategorieAnnonceColors' => json_encode($categorieAnnonceColor),
            'CategorieAnnonceCount'    =>  json_encode($categorieAnnonceCount),
        ]);
    }
}
