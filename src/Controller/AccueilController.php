<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use App\Repository\ImmobilierRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function accueil(
        VilleRepository $villeRepository, 
        ImmobilierRepository $immobilierRepository,
        AnnonceRepository $annonceRepository
    ): Response
    {   

        $villes = $villeRepository->findAll();

        $futuredImmobiliers = $immobilierRepository->findFuturedDesc();

        return $this->render('accueil/accueil.html.twig', [
            'villes' => $villes,
            'futuredImmobiliers' => $futuredImmobiliers,
            'latestAnnonces' => $annonceRepository->findLastest(),
        ]);
    }
}
