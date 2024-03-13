<?php

namespace App\Entity;

use App\Repository\LigneNoteHonoraireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneNoteHonoraireRepository::class)]
class LigneNoteHonoraire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?FormationAssurer $designation = null;

    #[ORM\Column]
    private ?int $qantite = null;

    #[ORM\Column]
    private ?float $prixUnitaire = null;

    #[ORM\Column]
    private ?float $prixTotalHT = null;

    #[ORM\ManyToOne(inversedBy: 'ligneNoteHonoraires', cascade: ['remove'])]
    private ?NoteHonoraire $noteHonoraire = null;


    #[ORM\ManyToOne(inversedBy: 'ligneNoteHonoraires')]
    private ?Unite $unite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?FormationAssurer
    {
        return $this->designation;
    }

    public function setDesignation(FormationAssurer $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    public function getQantite(): ?int
    {
        return $this->qantite;
    }

    public function setQantite(int $qantite): static
    {
        $this->qantite = $qantite;

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

    public function getPrixTotalHT(): ?float
    {
        return $this->prixTotalHT;
    }

    public function setPrixTotalHT(float $prixTotalHT): static
    {
        $this->prixTotalHT = $prixTotalHT;

        return $this;
    }

    public function getNoteHonoraire(): ?NoteHonoraire
    {
        return $this->noteHonoraire;
    }

    public function setNoteHonoraire(?NoteHonoraire $noteHonoraire): static
    {
        $this->noteHonoraire = $noteHonoraire;

        return $this;
    }

    public function getUnite(): ?Unite
    {
        return $this->unite;
    }

    public function setUnite(?Unite $unite): static
    {
        $this->unite = $unite;

        return $this;
    }
}
