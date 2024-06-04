<?php

namespace App\Entity;

use App\Repository\PayementFactureAchatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PayementFactureAchatRepository::class)]
class PayementFactureAchat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datePayement = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numero_cheque = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_cheque = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $banque = null;

    #[ORM\ManyToOne(inversedBy: 'payementFactureAchats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ModePayement $modePayement = null;

    #[ORM\ManyToOne(inversedBy: 'payementFactureAchats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FactureAchat $factureAchat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePayement(): ?\DateTimeInterface
    {
        return $this->datePayement;
    }

    public function setDatePayement(\DateTimeInterface $datePayement): static
    {
        $this->datePayement = $datePayement;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getNumeroCheque(): ?string
    {
        return $this->numero_cheque;
    }

    public function setNumeroCheque(?string $numero_cheque): static
    {
        $this->numero_cheque = $numero_cheque;

        return $this;
    }

    public function getDateCheque(): ?\DateTimeInterface
    {
        return $this->date_cheque;
    }

    public function setDateCheque(?\DateTimeInterface $date_cheque): static
    {
        $this->date_cheque = $date_cheque;

        return $this;
    }

    public function getBanque(): ?string
    {
        return $this->banque;
    }

    public function setBanque(?string $banque): static
    {
        $this->banque = $banque;

        return $this;
    }

    public function getModePayement(): ?ModePayement
    {
        return $this->modePayement;
    }

    public function setModePayement(?ModePayement $modePayement): static
    {
        $this->modePayement = $modePayement;

        return $this;
    }

    public function getFactureAchat(): ?FactureAchat
    {
        return $this->factureAchat;
    }

    public function setFactureAchat(?FactureAchat $factureAchat): static
    {
        $this->factureAchat = $factureAchat;

        return $this;
    }
}
