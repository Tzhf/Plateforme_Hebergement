<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GestionnaireRepository")
 * @UniqueEntity("username", message="Cet utilisateur est déjà enregistré")
 * @UniqueEntity("email", message="Cet email est déjà utilisé")
 */
class Gestionnaire implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank(message="Vous devez renseigner un nom du gestionnaire")
     * @Assert\Length(min=3, max=40, 
     *      minMessage="Le nom du gestionnaire doit contenir au moins {{ limit }} caractères", 
     *      maxMessage="Le nom du gestonnaire peut contenir au maximum {{ limit }} caractères"
     * )
     */
    private $username;
    
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank(message="Vous devez renseigner une adresse email")
     * @Assert\Email(message = "{{ value }} n'est pas une adresse email valide")
     */
    private $email;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner un mot de passe")
     * @Assert\Length(min=5, max=11,
     *      minMessage="Votre mot de passe doit contenir au moins 5 caractères",
     *      maxMessage="Votre mot de passe ne peut pas contenir plus de {{ limit }} caractères")
     */
    private $password;
    
    /**
     * @Assert\NotBlank(message="Veuillez confirmer votre mot de passe")
     * @Assert\EqualTo(propertyPath="password", message="Les mots de passe ne sont pas identiques")
     * @Assert\Length(min=5, max=11, 
     *      minMessage="Votre mot de passe doit contenir au moins 5 caractères")
     *      maxMessage="Votre mot de passe ne peut pas contenir plus de {{ limit }} caractères")
     */
    public $confirm_password;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ville", inversedBy="gestionnaires")
     */
    private $ville;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default" : 0})
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Logement", mappedBy="gestionnaire", orphanRemoval=true)
     */
    private $logements;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dispositif", inversedBy="gestionnaires")
     */
    private $dispositif;

    public function __construct()
    {
        $this->logements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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
            $logement->setGestionnaire($this);
        }

        return $this;
    }

    public function removeLogement(Logement $logement): self
    {
        if ($this->logements->contains($logement)) {
            $this->logements->removeElement($logement);
            // set the owning side to null (unless already changed)
            if ($logement->getGestionnaire() === $this) {
                $logement->setGestionnaire(null);
            }
        }

        return $this;
    }

    public function getDispositif(): ?Dispositif
    {
        return $this->dispositif;
    }

    public function setDispositif(?Dispositif $dispositif): self
    {
        $this->dispositif = $dispositif;

        return $this;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getRole() :?bool
    {
        return $this->role; 
    }

    public function setRole(bool $role): self
    {
        $this->role = $role;

        return $this; 
    }

    public function getSalt() {}

    public function eraseCredentials() {}
}
