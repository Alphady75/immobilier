<?php

namespace App\Twig;

use App\Repository\ContactRepository;
use App\Repository\AnnonceRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $contactRepository;

    private $annonceRepository;

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function __construct(ContactRepository $contactRepository, AnnonceRepository $annonceRepository){

        $this->contactRepository = $contactRepository;
        $this->annonceRepository = $annonceRepository;

    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('messagesNonLu', [$this, 'getMessageNonLu']),
            new TwigFunction('latestAnnonces', [$this, 'getLatestAnnonces']),
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
}
