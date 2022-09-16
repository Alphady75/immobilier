<?php

namespace App\Entity;

use App\Repository\ImmobilierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Entity\Traits\Timestamp;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ImmobilierRepository::class)]
#[ORM\HasLifecycleCallbacks]
/**
 * @Vich\Uploadable
 */
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
    #[ORM\JoinColumn(nullable: true)]
    private $ville;

    #[ORM\OneToMany(mappedBy: 'immobilier', targetEntity: ImmobilierMedia::class)]
    #[ORM\JoinColumn(nullable: true)]
    private $immobilierMedia;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'immobiliers')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $vendu;

    #[ORM\OneToMany(mappedBy: 'immobilier', targetEntity: Contact::class)]
    private $contacts;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $climatisation;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $piscine;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $chauffageCentral;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $spaMassages;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $gym;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $alarme;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $wifi;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $parking;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $chambres;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $salleBain;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $garage;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $tailleGarage;

    #[ORM\Column(type: 'date', nullable: true)]
    private $anneeConstrunction;

    public function __construct()
    {
        $this->immobilierMedia = new ArrayCollection();
        $this->contacts = new ArrayCollection();
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

    public function __toString(){
        return $this->name;
    }

    public function getVendu(): ?bool
    {
        return $this->vendu;
    }

    public function setVendu(?bool $vendu): self
    {
        $this->vendu = $vendu;

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setImmobilier($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getImmobilier() === $this) {
                $contact->setImmobilier(null);
            }
        }

        return $this;
    }

    public function getClimatisation(): ?bool
    {
        return $this->climatisation;
    }

    public function setClimatisation(?bool $climatisation): self
    {
        $this->climatisation = $climatisation;

        return $this;
    }

    public function getPiscine(): ?bool
    {
        return $this->piscine;
    }

    public function setPiscine(?bool $piscine): self
    {
        $this->piscine = $piscine;

        return $this;
    }

    public function getChauffageCentral(): ?bool
    {
        return $this->chauffageCentral;
    }

    public function setChauffageCentral(?bool $chauffageCentral): self
    {
        $this->chauffageCentral = $chauffageCentral;

        return $this;
    }

    public function getSpaMassages(): ?bool
    {
        return $this->spaMassages;
    }

    public function setSpaMassages(?bool $spaMassages): self
    {
        $this->spaMassages = $spaMassages;

        return $this;
    }

    public function getGym(): ?bool
    {
        return $this->gym;
    }

    public function setGym(?bool $gym): self
    {
        $this->gym = $gym;

        return $this;
    }

    public function getAlarme(): ?bool
    {
        return $this->alarme;
    }

    public function setAlarme(?bool $alarme): self
    {
        $this->alarme = $alarme;

        return $this;
    }

    public function getWifi(): ?bool
    {
        return $this->wifi;
    }

    public function setWifi(?bool $wifi): self
    {
        $this->wifi = $wifi;

        return $this;
    }

    public function getParking(): ?bool
    {
        return $this->parking;
    }

    public function setParking(?bool $parking): self
    {
        $this->parking = $parking;

        return $this;
    }

    public function getChambres(): ?int
    {
        return $this->chambres;
    }

    public function setChambres(?int $chambres): self
    {
        $this->chambres = $chambres;

        return $this;
    }

    public function getSalleBain(): ?int
    {
        return $this->salleBain;
    }

    public function setSalleBain(?int $salleBain): self
    {
        $this->salleBain = $salleBain;

        return $this;
    }

    public function getGarage(): ?int
    {
        return $this->garage;
    }

    public function setGarage(?int $garage): self
    {
        $this->garage = $garage;

        return $this;
    }

    public function getTailleGarage(): ?int
    {
        return $this->tailleGarage;
    }

    public function setTailleGarage(?int $tailleGarage): self
    {
        $this->tailleGarage = $tailleGarage;

        return $this;
    }

    public function getAnneeConstrunction(): ?\DateTimeInterface
    {
        return $this->anneeConstrunction;
    }

    public function setAnneeConstrunction(?\DateTimeInterface $anneeConstrunction): self
    {
        $this->anneeConstrunction = $anneeConstrunction;

        return $this;
    }
}
