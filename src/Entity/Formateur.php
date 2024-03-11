<?php

namespace App\Entity;

use App\Repository\FormateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormateurRepository::class)]
class Formateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $cin = null;

    #[ORM\Column(length: 25)]
    private ?string $telephone = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToMany(targetEntity: Domaine::class, inversedBy: 'formateurs')]
    private Collection $domaine;

    #[ORM\OneToMany(targetEntity: FormationAssurer::class, mappedBy: 'formateur')]
    private Collection $formationAssurers;

    #[ORM\OneToMany(targetEntity: NoteHonoraire::class, mappedBy: 'formateur')]
    private Collection $noteHonoraires;

    public function __construct()
    {
        $this->domaine = new ArrayCollection();
        $this->formationAssurers = new ArrayCollection();
        $this->noteHonoraires = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

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

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): static
    {
        $this->cin = $cin;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Domaine>
     */
    public function getDomaine(): Collection
    {
        return $this->domaine;
    }

    public function addDomaine(Domaine $domaine): static
    {
        if (!$this->domaine->contains($domaine)) {
            $this->domaine->add($domaine);
        }

        return $this;
    }

    public function removeDomaine(Domaine $domaine): static
    {
        $this->domaine->removeElement($domaine);

        return $this;
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
            $formationAssurer->setFormateur($this);
        }

        return $this;
    }

    public function removeFormationAssurer(FormationAssurer $formationAssurer): static
    {
        if ($this->formationAssurers->removeElement($formationAssurer)) {
            // set the owning side to null (unless already changed)
            if ($formationAssurer->getFormateur() === $this) {
                $formationAssurer->setFormateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, NoteHonoraire>
     */
    public function getNoteHonoraires(): Collection
    {
        return $this->noteHonoraires;
    }

    public function addNoteHonoraire(NoteHonoraire $noteHonoraire): static
    {
        if (!$this->noteHonoraires->contains($noteHonoraire)) {
            $this->noteHonoraires->add($noteHonoraire);
            $noteHonoraire->setFormateur($this);
        }

        return $this;
    }

    public function removeNoteHonoraire(NoteHonoraire $noteHonoraire): static
    {
        if ($this->noteHonoraires->removeElement($noteHonoraire)) {
            // set the owning side to null (unless already changed)
            if ($noteHonoraire->getFormateur() === $this) {
                $noteHonoraire->setFormateur(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom.' '.$this->prenom;
    }
}
