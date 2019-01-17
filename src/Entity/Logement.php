<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LogementRepository")
 */
class Logement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     * @Assert\Length(max = 5, maxMessage = "Le numéro de la rue ne peut pas dépasser {{ limit }} caractères")
     */
    private $numRue;

    /**
     * @ORM\Column(type="string", length=70)
     * @Assert\NotBlank(message = "Vous devez renseigner le nom de la rue")
     * @Assert\Length(min = 3, max = 70,
     *      minMessage = "Le nom de la rue est trop court",
     *      maxMessage = "Le nom de la rue ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    private $nomRue;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ville", inversedBy="logements")
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     * @Assert\Length(max = 5, maxMessage = "Le numéro du bâtiment ne peut pas dépasser {{ limit }} caractères")
     */
    private $numBat;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     * @Assert\Length(max = 5, maxMessage = "Le numéro du logement ne peut pas dépasser {{ limit }} caractères")
     */
    private $numLgmt;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     * @Assert\Length(max = 5, maxMessage = "Le numéro de l'étage ne peut pas dépasser {{ limit }} caractères")
     */
    private $etage;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     * @Assert\Length(max = 5, maxMessage = "Ce champ ne peut pas dépasser {{ limit }} caractères")
     */
    private $typologie;

    /**
     * @ORM\Column(type="integer", length=5)
     * @Assert\NotBlank(message="Vous devez renseigner la capacité du logement")
     * @Assert\Range(min = 0, max = 50,
     *      minMessage = "La capacité ne peut pas être négative",
     *      maxMessage = "La capacité ne peut pas dépasser {{ limit }} personnes"
     * )
     */
    private $capacite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max = 255, maxMessage = "La description ne peut pas dépasser {{ limit }} charactères")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Gestionnaire", inversedBy="logements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gestionnaire;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Location", mappedBy="logement", orphanRemoval=true)
     */
    private $locations;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumRue(): ?string
    {
        return $this->numRue;
    }

    public function setNumRue(?string $numRue): self
    {
        $this->numRue = $numRue;

        return $this;
    }

    public function getNomRue(): ?string
    {
        return $this->nomRue;
    }

    public function setNomRue(?string $nomRue): self
    {
        $this->nomRue = $nomRue;

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

    public function getNumBat(): ?string
    {
        return $this->numBat;
    }

    public function setNumBat(?string $numBat): self
    {
        $this->numBat = $numBat;

        return $this;
    }

    public function getNumLgmt(): ?string
    {
        return $this->numLgmt;
    }

    public function setNumLgmt(?string $numLgmt): self
    {
        $this->numLgmt = $numLgmt;

        return $this;
    }

    public function getEtage(): ?string
    {
        return $this->etage;
    }

    public function setEtage(?string $etage): self
    {
        $this->etage = $etage;

        return $this;
    }

    public function getTypologie(): ?string
    {
        return $this->typologie;
    }

    public function setTypologie(?string $typologie): self
    {
        $this->typologie = $typologie;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;

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

    public function getGestionnaire(): ?Gestionnaire
    {
        return $this->gestionnaire;
    }

    public function setGestionnaire(?Gestionnaire $gestionnaire): self
    {
        $this->gestionnaire = $gestionnaire;

        return $this;
    }

    /**
     * @return Collection|Location[]
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): self
    {
        if (!$this->locations->contains($location)) {
            $this->locations[] = $location;
            $location->setLogement($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->contains($location)) {
            $this->locations->removeElement($location);
            // set the owning side to null (unless already changed)
            if ($location->getLogement() === $this) {
                $location->setLogement(null);
            }
        }

        return $this;
    }

}
