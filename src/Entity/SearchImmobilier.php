<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\CategorieImmobilier;
use App\Entity\Ville;
use Doctrine\Persistence\ObjectManager;

class SearchImmobilier
{
    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string|null
     */
    public $q = null;

    /**
     * @var string|null
     */
    public $type;

    /**
     * @var int|null
     */
    public $tarif;

    /**
     * @var string|null
     */
    public $statut;

    /**
     * @var int|null
     */
    public $minTarif;

    /**
     * @var int|null
     */
    public $maxTarif;

    /**
     * @var ArrayCollection|null
     */
    public $categories;

    /**
     * @var ArrayCollection|null
     */
    public $villes;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->villes = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getCategories(): ?ArrayCollection
    {
        return $this->categories;
    }

    /**
     * @param ArrayCollection $categories
     */
    public function setCategories(?ArrayCollection $categories): void
    {
        $this->categories = $categories;
    }

    /**
     * @return ArrayCollection
     */
    public function getVilles(): ?ArrayCollection
    {
        return $this->villes;
    }

    /**
     * @param ArrayCollection $villes
     */
    public function setVilles(?ArrayCollection $villes): void
    {
        $this->villes = $villes;
    }
}