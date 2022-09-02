<?php

namespace App\Entity;

use App\Repository\ImmobilierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestamp;

#[ORM\Entity(repositoryClass: ImmobilierRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Immobilier
{
    use Timestamp;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $slug;

    /**
     * @Vich\UploadableField(mapping="immobiliers_images", fileNameProperty="imageName")
     * @var File|null
     * @Assert\Image(maxSize="5M", maxSizeMessage="Image trop volumineuse maximum 5Mb")
     * @Assert\Image(mimeTypes = {"image/jpeg", "image/jpg", "image/png"}, mimeTypesMessage = "Mauvais format d'image (jpeg, jpg et png)")
    **/
    private $imageFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageName;

    #[ORM\Column(type: 'float')]
    private $tarif;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $reference;

    #[ORM\Column(type: 'float', nullable: true)]
    private $surfaceMin;

    #[ORM\Column(type: 'float', nullable: true)]
    private $surfaceMax;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $type;

    #[ORM\ManyToOne(targetEntity: CategorieImmobilier::class, inversedBy: 'immobiliers')]
    private $categorie;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'string', length: 60, nullable: true)]
    private $statut;

    #[ORM\Column(type: 'boolean')]
    private $online;

    #[ORM\ManyToOne(targetEntity: Ville::class, inversedBy: 'immobiliers')]
    private $ville;

    #[ORM\OneToMany(mappedBy: 'immobilier', targetEntity: ImmobilierMedia::class)]
    private $immobilierMedia;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'immobiliers')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    public function __construct()
    {
        $this->immobilierMedia = new ArrayCollection();
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

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(float $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getSurfaceMin(): ?float
    {
        return $this->surfaceMin;
    }

    public function setSurfaceMin(?float $surfaceMin): self
    {
        $this->surfaceMin = $surfaceMin;

        return $this;
    }

    public function getSurfaceMax(): ?float
    {
        return $this->surfaceMax;
    }

    public function setSurfaceMax(?float $surfaceMax): self
    {
        $this->surfaceMax = $surfaceMax;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCategorie(): ?CategorieImmobilier
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieImmobilier $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): self
    {
        $this->online = $online;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection|ImmobilierMedia[]
     */
    public function getImmobilierMedia(): Collection
    {
        return $this->immobilierMedia;
    }

    public function addImmobilierMedium(ImmobilierMedia $immobilierMedium): self
    {
        if (!$this->immobilierMedia->contains($immobilierMedium)) {
            $this->immobilierMedia[] = $immobilierMedium;
            $immobilierMedium->setImmobilier($this);
        }

        return $this;
    }

    public function removeImmobilierMedium(ImmobilierMedia $immobilierMedium): self
    {
        if ($this->immobilierMedia->removeElement($immobilierMedium)) {
            // set the owning side to null (unless already changed)
            if ($immobilierMedium->getImmobilier() === $this) {
                $immobilierMedium->setImmobilier(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdated(new \DateTimeImmutable());
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
}
