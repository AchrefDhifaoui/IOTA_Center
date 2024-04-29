<?php

namespace App\Entity;

use App\Repository\LigneFactureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneFactureRepository::class)]
class LigneFacture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    private ?FormationAssurer $designation = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column]
    private ?float $prixUnitaire = null;

    #[ORM\ManyToOne]
    private ?Unite $Unite = null;

    #[ORM\Column]
    private ?float $totalHT = null;

    #[ORM\ManyToOne(inversedBy: 'ligneFactures', cascade: ['remove'])]
    private ?Facture $Facture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $des_Manuel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?FormationAssurer
    {
        return $this->designation;
    }

    public function setDesignation(?FormationAssurer $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrixUnitaire(): ?float
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(float $prixUnitaire): static
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }

    public function getUnite(): ?Unite
    {
        return $this->Unite;
    }

    public function setUnite(?Unite $Unite): static
    {
        $this->Unite = $Unite;

        return $this;
    }

    public function getTotalHT(): ?float
    {
        return $this->totalHT;
    }

    public function setTotalHT(float $totalHT): static
    {
        $this->totalHT = $totalHT;

        return $this;
    }

    public function getFacture(): ?Facture
    {
        return $this->Facture;
    }

    public function setFacture(?Facture $Facture): static
    {
        $this->Facture = $Facture;

        return $this;
    }

    public function getDesManuel(): ?string
    {
        return $this->des_Manuel;
    }

    public function setDesManuel(?string $des_Manuel): static
    {
        $this->des_Manuel = $des_Manuel;

        return $this;
    }
}
