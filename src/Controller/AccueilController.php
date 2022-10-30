<?php

namespace App\Controller;

use App\Entity\SearchImmobilier;
use App\Form\HomeSearchImmobilierType;
use App\Repository\AnnonceRepository;
use App\Repository\ImmobilierRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function accueil(
        VilleRepository $villeRepository, 
        ImmobilierRepository $immobilierRepository,
        AnnonceRepository $annonceRepository, Request $request
    ): Response
    {
        $villes = $villeRepository->findAll();

        $futuredImmobiliers = $immobilierRepository->findFuturedDesc();

        $immobilier = new SearchImmobilier();

        $form = $this->createForm(HomeSearchImmobilierType::class, $immobilier, [
            'action' => $this->generateUrl('immobiliers'),
            'method' => 'GET',
        ]);

        $form->handleRequest($request);

        return $this->render('accueil/accueil.html.twig', [
            'villes' => $villes,
            'form' => $form->createView(),
            'firstSlider' => $futuredImmobiliers[0],
            'futuredImmobiliers' => $futuredImmobiliers,
            'latestAnnonces' => $annonceRepository->findLastest(),
        ]);
    }
}
