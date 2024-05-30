<?php

namespace App\Entity;

use App\Repository\ModePayementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModePayementRepository::class)]
class ModePayement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $mode = null;

    /**
     * @var Collection<int, Payement>
     */
    #[ORM\OneToMany(targetEntity: Payement::class, mappedBy: 'modePayement')]
    private Collection $payements;

    /**
     * @var Collection<int, PayementFactureAchat>
     */
    #[ORM\OneToMany(targetEntity: PayementFactureAchat::class, mappedBy: 'modePayement')]
    private Collection $payementFactureAchats;

    /**
     * @var Collection<int, PayementNoteHonoraire>
     */
    #[ORM\OneToMany(targetEntity: PayementNoteHonoraire::class, mappedBy: 'modePayement')]
    private Collection $payementNoteHonoraires;

    public function __construct()
    {
        $this->payements = new ArrayCollection();
        $this->payementFactureAchats = new ArrayCollection();
        $this->payementNoteHonoraires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): static
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return Collection<int, Payement>
     */
    public function getPayements(): Collection
    {
        return $this->payements;
    }

    public function addPayement(Payement $payement): static
    {
        if (!$this->payements->contains($payement)) {
            $this->payements->add($payement);
            $payement->setModePayement($this);
        }

        return $this;
    }

    public function removePayement(Payement $payement): static
    {
        if ($this->payements->removeElement($payement)) {
            // set the owning side to null (unless already changed)
            if ($payement->getModePayement() === $this) {
                $payement->setModePayement(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->mode;
    }

    /**
     * @return Collection<int, PayementFactureAchat>
     */
    public function getPayementFactureAchats(): Collection
    {
        return $this->payementFactureAchats;
    }

    public function addPayementFactureAchat(PayementFactureAchat $payementFactureAchat): static
    {
        if (!$this->payementFactureAchats->contains($payementFactureAchat)) {
            $this->payementFactureAchats->add($payementFactureAchat);
            $payementFactureAchat->setModePayement($this);
        }

        return $this;
    }

    public function removePayementFactureAchat(PayementFactureAchat $payementFactureAchat): static
    {
        if ($this->payementFactureAchats->removeElement($payementFactureAchat)) {
            // set the owning side to null (unless already changed)
            if ($payementFactureAchat->getModePayement() === $this) {
                $payementFactureAchat->setModePayement(null);
            }
        }

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
            $payementNoteHonoraire->setModePayement($this);
        }

        return $this;
    }

    public function removePayementNoteHonoraire(PayementNoteHonoraire $payementNoteHonoraire): static
    {
        if ($this->payementNoteHonoraires->removeElement($payementNoteHonoraire)) {
            // set the owning side to null (unless already changed)
            if ($payementNoteHonoraire->getModePayement() === $this) {
                $payementNoteHonoraire->setModePayement(null);
            }
        }

        return $this;
    }

}
