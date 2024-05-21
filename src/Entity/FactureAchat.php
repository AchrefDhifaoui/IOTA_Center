<?php

namespace App\Entity;

use App\Repository\FactureAchatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureAchatRepository::class)]
class FactureAchat
{
    const ETAT_NON_PAYE = 'Non payé';
    const ETAT_PAYE = 'payé';
    const ETAT_PARTIELLEMENT_PAYE = 'partiellement payé';
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'factureAchats')]
    private ?Fournisseur $fournisseur = null;

    #[ORM\Column]
    private ?float $TotalHT = null;

    #[ORM\Column]
    private ?float $TotalTVA = null;

    #[ORM\Column]
    private ?float $timbre = null;

    #[ORM\Column]
    private ?float $TotalTTC = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pieceJointe = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column(length: 255)]
    private ?string $numero = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): static
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getTotalHT(): ?float
    {
        return $this->TotalHT;
    }

    public function setTotalHT(float $TotalHT): static
    {
        $this->TotalHT = $TotalHT;

        return $this;
    }

    public function getTotalTVA(): ?float
    {
        return $this->TotalTVA;
    }

    public function setTotalTVA(float $TotalTVA): static
    {
        $this->TotalTVA = $TotalTVA;

        return $this;
    }

    public function getTimbre(): ?float
    {
        return $this->timbre;
    }

    public function setTimbre(float $timbre): static
    {
        $this->timbre = $timbre;

        return $this;
    }

    public function getTotalTTC(): ?float
    {
        return $this->TotalTTC;
    }

    public function setTotalTTC(float $TotalTTC): static
    {
        $this->TotalTTC = $TotalTTC;

        return $this;
    }

    public function getPieceJointe(): ?string
    {
        return $this->pieceJointe;
    }

    public function setPieceJointe(?string $pieceJointe): static
    {
        $this->pieceJointe = $pieceJointe;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
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
}
