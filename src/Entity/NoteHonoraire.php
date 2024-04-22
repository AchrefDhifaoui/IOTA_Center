<?php

namespace App\Entity;

use App\Repository\NoteHonoraireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteHonoraireRepository::class)]
class NoteHonoraire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numero = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(targetEntity: LigneNoteHonoraire::class, mappedBy: 'noteHonoraire', cascade: ['persist','remove'])]
    private Collection $ligneNoteHonoraires;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ParametreIota $client = null;

    #[ORM\ManyToOne(inversedBy: 'noteHonoraires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formateur $formateur = null;

    #[ORM\Column(nullable: true)]
    private ?bool $etat = null;





    public function __construct()
    {
        $this->ligneNoteHonoraires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, LigneNoteHonoraire>
     */
    public function getLigneNoteHonoraires(): Collection
    {
        return $this->ligneNoteHonoraires;
    }

    public function addLigneNoteHonoraire(LigneNoteHonoraire $ligneNoteHonoraire): static
    {
        if (!$this->ligneNoteHonoraires->contains($ligneNoteHonoraire)) {
            $this->ligneNoteHonoraires->add($ligneNoteHonoraire);
            $ligneNoteHonoraire->setNoteHonoraire($this);
        }

        return $this;
    }

    public function removeLigneNoteHonoraire(LigneNoteHonoraire $ligneNoteHonoraire): static
    {
        if ($this->ligneNoteHonoraires->removeElement($ligneNoteHonoraire)) {
            // set the owning side to null (unless already changed)
            if ($ligneNoteHonoraire->getNoteHonoraire() === $this) {
                $ligneNoteHonoraire->setNoteHonoraire(null);
            }
        }

        return $this;
    }

    public function getClient(): ?ParametreIota
    {
        return $this->client;
    }

    public function setClient(?ParametreIota $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getFormateur(): ?Formateur
    {
        return $this->formateur;
    }

    public function setFormateur(?Formateur $formateur): static
    {
        $this->formateur = $formateur;

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(?bool $etat): static
    {
        $this->etat = $etat;

        return $this;
    }




}
