<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OccupantRepository")
 */
class Occupant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank(message = "Vous devez renseigner le nom de l'occupant")
     * @Assert\Length(
     *      min = 2,
     *      max = 40,
     *      minMessage = "Le nom est trop court",
     *      maxMessage = "Le nom ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank(message = "Vous devez renseigner le prénom de l'occupant")
     * @Assert\Length(
     *      min = 2,
     *      max = 40,
     *      minMessage = "Le prénom est trop court",
     *      maxMessage = "Le prénom ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    private $prenom;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date(message = "Le format de la date est invalide")
     * @var string A "m-Y" formatted value
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      max = 30,
     *      minMessage = "La nombre d'enfants ne peut pas être négatif",
     *      maxMessage = "La nombre d'enfants ne peut pas dépasser {{ limit }}"
     * )
     */
    private $nbEnfant;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Location", mappedBy="occupant", orphanRemoval=true)
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getNbEnfant(): ?int
    {
        return $this->nbEnfant;
    }

    public function setNbEnfant(?int $nbEnfant): self
    {
        $this->nbEnfant = $nbEnfant;

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
            $location->setLocataire($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->contains($location)) {
            $this->locations->removeElement($location);
            // set the owning side to null (unless already changed)
            if ($location->getLocataire() === $this) {
                $location->setLocataire(null);
            }
        }

        return $this;
    }
}
