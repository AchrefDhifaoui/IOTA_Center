<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $detail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $objectifs = null;

    #[ORM\Column(length: 255 ,nullable: true)]
    private ?string $contenu = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $duree = null;

    #[ORM\ManyToMany(targetEntity: Mode::class)]
    public Collection $mode;

    #[ORM\ManyToOne(inversedBy: 'formations')]
    private ?Domaine $domaine = null;

    #[ORM\OneToMany(targetEntity: FormationAssurer::class, mappedBy: 'formation')]
    private Collection $formationAssurer;

    public function __construct()
    {
        $this->mode = new ArrayCollection();
        $this->formationAssurer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): string
    {
        return $this->titre = $titre;
    }


    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): static
    {
        $this->detail = $detail;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $brochureFilename): self
    {
        $this->image= $brochureFilename;

        return $this;
    }

    public function getObjectifs(): ?string
    {
        return $this->objectifs;
    }

    public function setObjectifs(?string $objectifs): static
    {
        $this->objectifs = $objectifs;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(?string $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * @return Collection<int, Mode>
     */
    public function getMode(): Collection
    {
        return $this->mode;
    }

    public function addMode(Mode $mode): static
    {
        if (!$this->mode->contains($mode)) {
            $this->mode->add($mode);
        }

        return $this;
    }

    public function removeMode(Mode $mode): static
    {
        $this->mode->removeElement($mode);

        return $this;
    }

    public function getDomaine(): ?Domaine
    {
        return $this->domaine;
    }

    public function setDomaine(?Domaine $domaine): static
    {
        $this->domaine = $domaine;

        return $this;
    }

    /**
     * @return Collection<int, FormationAssurer>
     */
    public function getFormationAssurer(): Collection
    {
        return $this->formationAssurer;
    }

    public function addFormationAssurer(FormationAssurer $formationAssurer): static
    {
        if (!$this->formationAssurer->contains($formationAssurer)) {
            $this->formationAssurer->add($formationAssurer);
            $formationAssurer->setFormation($this);
        }

        return $this;
    }

    public function removeFormationAssurer(FormationAssurer $formationAssurer): static
    {
        if ($this->formationAssurer->removeElement($formationAssurer)) {
            // set the owning side to null (unless already changed)
            if ($formationAssurer->getFormation() === $this) {
                $formationAssurer->setFormation(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->titre;
    }

}
