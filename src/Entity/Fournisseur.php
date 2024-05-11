<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FournisseurRepository::class)]
class Fournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $matriculeFiscale = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    /**
     * @var Collection<int, FactureAchat>
     */
    #[ORM\OneToMany(targetEntity: FactureAchat::class, mappedBy: 'fournisseur')]
    private Collection $factureAchats;

    public function __construct()
    {
        $this->factureAchats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMatriculeFiscale(): ?string
    {
        return $this->matriculeFiscale;
    }

    public function setMatriculeFiscale(?string $matriculeFiscale): static
    {
        $this->matriculeFiscale = $matriculeFiscale;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection<int, FactureAchat>
     */
    public function getFactureAchats(): Collection
    {
        return $this->factureAchats;
    }

    public function addFactureAchat(FactureAchat $factureAchat): static
    {
        if (!$this->factureAchats->contains($factureAchat)) {
            $this->factureAchats->add($factureAchat);
            $factureAchat->setFournisseur($this);
        }

        return $this;
    }

    public function removeFactureAchat(FactureAchat $factureAchat): static
    {
        if ($this->factureAchats->removeElement($factureAchat)) {
            // set the owning side to null (unless already changed)
            if ($factureAchat->getFournisseur() === $this) {
                $factureAchat->setFournisseur(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom;
    }

}
