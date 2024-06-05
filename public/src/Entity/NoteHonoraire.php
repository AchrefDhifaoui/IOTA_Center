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
    const ETAT_NON_PAYE = 'Non payé';
    const ETAT_PAYE = 'payé';
    const ETAT_PARTIELLEMENT_PAYE = 'partiellement payé';
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

    #[ORM\Column]
    private ?string $etat = self::ETAT_NON_PAYE;

    #[ORM\ManyToOne]
    private ?RS $RS = null;

    /**
     * @var Collection<int, PayementNoteHonoraire>
     */
    #[ORM\OneToMany(targetEntity: PayementNoteHonoraire::class, mappedBy: 'note')]
    private Collection $payementNoteHonoraires;





    public function __construct()
    {
        $this->ligneNoteHonoraires = new ArrayCollection();
        $this->payementNoteHonoraires = new ArrayCollection();
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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getRS(): ?RS
    {
        return $this->RS;
    }

    public function setRS(?RS $RS): static
    {
        $this->RS = $RS;

        return $this;
    }

    /**
     * @return Collection<int, PayementNoteHonoraire>
     */
    public function getPayementNoteHonoraires(): Collection
    {
        return $this->payementNoteHonoraires;
    }

    public function addPayementNoteHonoraire(PayementNoteHonoraire $payementNoteHonoraire): static
    {
        if (!$this->payementNoteHonoraires->contains($payementNoteHonoraire)) {
            $this->payementNoteHonoraires->add($payementNoteHonoraire);
            $payementNoteHonoraire->setNote($this);
        }

        return $this;
    }

    public function removePayementNoteHonoraire(PayementNoteHonoraire $payementNoteHonoraire): static
    {
        if ($this->payementNoteHonoraires->removeElement($payementNoteHonoraire)) {
            // set the owning side to null (unless already changed)
            if ($payementNoteHonoraire->getNote() === $this) {
                $payementNoteHonoraire->setNote(null);
            }
        }

        return $this;
    }




}
