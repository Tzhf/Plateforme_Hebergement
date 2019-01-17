<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VilleRepository")
 * @UniqueEntity("nom", message="Cette ville est déjà présente dans la base de données")
 */
class Ville
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Vous devez renseigner le nom de la ville")
     * @Assert\Length(
     *      max = 70,
     *      maxMessage = "Le nom de la ville ne peut pas dépasser {{ limit }} caractères"
     * )
     */ 
    
    private $nom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Regex("/^[0-9]{5}$/", message="Cette valeur n'est pas valide")
     */
    private $codePostal;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Logement", mappedBy="ville")
     */
    private $logements;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Gestionnaire", mappedBy="ville")
     */
    private $gestionnaires;

    public function __construct()
    {
        $this->logements = new ArrayCollection();
        $this->gestionnaires = new ArrayCollection();
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

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(?int $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * @return Collection|Logement[]
     */
    public function getLogements(): Collection
    {
        return $this->logements;
    }

    public function addLogement(Logement $logement): self
    {
        if (!$this->logements->contains($logement)) {
            $this->logements[] = $logement;
            $logement->setVille($this);
        }

        return $this;
    }

    public function removeLogement(Logement $logement): self
    {
        if ($this->logements->contains($logement)) {
            $this->logements->removeElement($logement);
            // set the owning side to null (unless already changed)
            if ($logement->getVille() === $this) {
                $logement->setVille(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Gestionnaire[]
     */
    public function getGestionnaires(): Collection
    {
        return $this->gestionnaires;
    }

    public function addGestionnaire(Gestionnaire $gestionnaire): self
    {
        if (!$this->gestionnaires->contains($gestionnaire)) {
            $this->gestionnaires[] = $gestionnaire;
            $gestionnaire->setVille($this);
        }

        return $this;
    }

    public function removeGestionnaire(Gestionnaire $gestionnaire): self
    {
        if ($this->gestionnaires->contains($gestionnaire)) {
            $this->gestionnaires->removeElement($gestionnaire);
            // set the owning side to null (unless already changed)
            if ($gestionnaire->getVille() === $this) {
                $gestionnaire->setVille(null);
            }
        }

        return $this;
    }
}
