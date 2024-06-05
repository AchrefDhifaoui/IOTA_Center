<?php

namespace App\Entity;

use App\Repository\UniteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UniteRepository::class)]
class Unite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\OneToMany(targetEntity: LigneNoteHonoraire::class, mappedBy: 'unite')]
    private Collection $ligneNoteHonoraires;

    #[ORM\OneToMany(targetEntity: FormationAssurer::class, mappedBy: 'unite')]
    private Collection $formationAssurers;


    public function __construct()
    {
        $this->ligneNoteHonoraires = new ArrayCollection();
        $this->formationAssurers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

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
            $ligneNoteHonoraire->setUnite($this);
        }

        return $this;
    }

    public function removeLigneNoteHonoraire(LigneNoteHonoraire $ligneNoteHonoraire): static
    {
        if ($this->ligneNoteHonoraires->removeElement($ligneNoteHonoraire)) {
            // set the owning side to null (unless already changed)
            if ($ligneNoteHonoraire->getUnite() === $this) {
                $ligneNoteHonoraire->setUnite(null);
            }
        }

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
            $formationAssurer->setUnite($this);
        }

        return $this;
    }

    public function removeFormationAssurer(FormationAssurer $formationAssurer): static
    {
        if ($this->formationAssurers->removeElement($formationAssurer)) {
            // set the owning side to null (unless already changed)
            if ($formationAssurer->getUnite() === $this) {
                $formationAssurer->setUnite(null);
            }
        }

        return $this;
    }
}
