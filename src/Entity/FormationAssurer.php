<?php

namespace App\Entity;

use App\Repository\FormationAssurerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationAssurerRepository::class)]
class FormationAssurer
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column]
    private ?int $quantite = null;


    #[ORM\ManyToOne(inversedBy: 'formationAssurer')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formation $formation = null;

    #[ORM\ManyToOne(inversedBy: 'formationAssurers')]
    private ?Formateur $formateur = null;



    #[ORM\ManyToOne(inversedBy: 'formationAssurers')]
    private ?Unite $unite = null;

    #[ORM\ManyToOne(inversedBy: 'formationAssurers')]
    private ?Client $Client = null;

    #[ORM\Column]
    private ?float $puFormation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

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


    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): static
    {
        $this->formation = $formation;

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








    public function __toString(): string
    {
        $dateString = $this->dateDebut ? $this->dateDebut->format('Y-m-d') : 'No Date';

        return $this->formation->getTitre() . ' (' . $dateString . ')';
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

    public function getClient(): ?Client
    {
        return $this->Client;
    }

    public function setClient(?Client $Client): static
    {
        $this->Client = $Client;

        return $this;
    }

    public function getPuFormation(): ?float
    {
        return $this->puFormation;
    }

    public function setPuFormation(float $puFormation): static
    {
        $this->puFormation = $puFormation;

        return $this;
    }






}
