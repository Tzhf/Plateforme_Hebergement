<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Logement", inversedBy="locations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $logement;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Occupant", cascade={"persist"}, inversedBy="locations")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid
     */
    private $occupant;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateEntree;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateSortie;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $isActive = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogement(): ?Logement
    {
        return $this->logement;
    }

    public function setLogement(?Logement $logement): self
    {
        $this->logement = $logement;

        return $this;
    }

    public function getOccupant(): ?Occupant
    {
        return $this->occupant;
    }

    public function setOccupant(?Occupant $occupant): self
    {
        $this->occupant = $occupant;

        return $this;
    }

    public function getDateEntree() : ?\DateTimeInterface
    {
        return $this->dateEntree;
    }

    public function setDateEntree(\DateTimeInterface $dateEntree = null) : self
    {
        $this->dateEntree = $dateEntree;

        return $this;
    }

    public function getDateSortie() : ?\DateTimeInterface
    {
        return $this->dateSortie;
    }

    public function setDateSortie(\DateTimeInterface $dateSortie = null) : self
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }

    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }
}
