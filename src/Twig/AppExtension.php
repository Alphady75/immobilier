<?php

namespace App\Twig;

use App\Repository\VilleRepository;
use App\Repository\CategorieImmobilierRepository;
use App\Repository\ContactRepository;
use App\Repository\AnnonceRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $contactRepository;

    private $annonceRepository;

    private $categorieImmobilierRepository;

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function __construct(
        ContactRepository $contactRepository,
        AnnonceRepository $annonceRepository,
        CategorieImmobilierRepository $categorieImmobilierRepository,
        VilleRepository $villeRepository,
    ){

        $this->contactRepository = $contactRepository;
        $this->annonceRepository = $annonceRepository;
        $this->categorieImmobilierRepository = $categorieImmobilierRepository;
        $this->villeRepository = $villeRepository;

    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('messagesNonLu', [$this, 'getMessageNonLu']),
            new TwigFunction('latestAnnonces', [$this, 'getLatestAnnonces']),
            new TwigFunction('villes', [$this, 'getVilles']),
            new TwigFunction('categorieImmobiliers', [$this, 'getCategoriesImmobilier']),
        ];
    }

    public function getMessageNonLu()
    {
        return $this->contactRepository->findBy([
            'lu' => 0,
        ]);
    }

    public function getLatestAnnonces()
    {
        return $this->annonceRepository->findLastest();
    }

    public function getVilles()
    {
        return $this->villeRepository->findBy([], ['name' => 'DESC']);
    }

    public function getCategoriesImmobilier()
    {
        return $this->categorieImmobilierRepository->findBy([], ['name' => 'DESC']);
    }
}
