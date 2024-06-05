<?php

namespace App\Entity;

use App\Repository\PayementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PayementRepository::class)]
class Payement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datePayement = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $numero_cheque_compte = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_cheque = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $banque = null;

    #[ORM\ManyToOne(inversedBy: 'payements')]
    private ?ModePayement $modePayement = null;

    #[ORM\ManyToOne(inversedBy: 'Payement')]
    private ?Facture $facture = null;

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

    public function getNumeroChequeCompte(): ?string
    {
        return $this->numero_cheque_compte;
    }

    public function setNumeroChequeCompte(?string $numero_cheque_compte): static
    {
        $this->numero_cheque_compte = $numero_cheque_compte;

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

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): static
    {
        $this->facture = $facture;

        return $this;
    }
}
