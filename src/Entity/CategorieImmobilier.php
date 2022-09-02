<?php

namespace App\Entity;

use App\Repository\CategorieImmobilierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestamp;

#[ORM\Entity(repositoryClass: CategorieImmobilierRepository::class)]
#[ORM\HasLifecycleCallbacks]
class CategorieImmobilier
{
    use Timestamp;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $name;

    #[ORM\Column(type: 'string', length: 100)]
    private $slug;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Immobilier::class)]
    private $immobiliers;

    public function __construct()
    {
        $this->immobiliers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Immobilier[]
     */
    public function getImmobiliers(): Collection
    {
        return $this->immobiliers;
    }

    public function addImmobilier(Immobilier $immobilier): self
    {
        if (!$this->immobiliers->contains($immobilier)) {
            $this->immobiliers[] = $immobilier;
            $immobilier->setCategorie($this);
        }

        return $this;
    }

    public function removeImmobilier(Immobilier $immobilier): self
    {
        if ($this->immobiliers->removeElement($immobilier)) {
            // set the owning side to null (unless already changed)
            if ($immobilier->getCategorie() === $this) {
                $immobilier->setCategorie(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
