<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 25)]
    private ?string $telephone = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column(length: 25)]
    private ?string $matriculeFiscale = null;



    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToMany(targetEntity: FormationAssurer::class, mappedBy: 'Client')]
    private Collection $formationAssurers;

    public function __construct()
    {
        $this->formationAssurers = new ArrayCollection();
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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMatriculeFiscale(): ?string
    {
        return $this->matriculeFiscale;
    }

    public function setMatriculeFiscale(string $matriculeFiscale): static
    {
        $this->matriculeFiscale = $matriculeFiscale;

        return $this;
    }







    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom;
    }

    /**
     * @return Collection<int, FormationAssurer>
     */
    public function getFormationAssurers(): Collection
    {
        return $this->formationAssurers;
    }

    public function addFormationAssurer(FormationAssurer $formationAssurer): static
    {
        if (!$this->formationAssurers->contains($formationAssurer)) {
            $this->formationAssurers->add($formationAssurer);
            $formationAssurer->setClient($this);
        }

        return $this;
    }

    public function removeFormationAssurer(FormationAssurer $formationAssurer): static
    {
        if ($this->formationAssurers->removeElement($formationAssurer)) {
            // set the owning side to null (unless already changed)
            if ($formationAssurer->getClient() === $this) {
                $formationAssurer->setClient(null);
            }
        }

        return $this;
    }










}
