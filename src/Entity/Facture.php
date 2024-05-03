<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
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
    private ?\DateTimeInterface $date_facture = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\ManyToOne]
    private ?TVA $tva = null;



    #[ORM\Column(length: 255)]
    private ?string $etat = self::ETAT_NON_PAYE;

    #[ORM\OneToMany(targetEntity: LigneFacture::class, mappedBy: 'Facture', cascade: ['persist','remove'])]
    private Collection $ligneFactures;



    #[ORM\Column(nullable: true)]
    private ?float $Total_HT = null;

    #[ORM\Column(nullable: true)]
    private ?float $Total_TVA = null;

    #[ORM\Column(nullable: true)]
    private ?float $Total_TTC = null;

    #[ORM\Column(nullable: true)]
    private ?float $timbre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isConfirmed = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pieceJoin_RS = null;

    public function __construct()
    {
        $this->ligneFactures = new ArrayCollection();
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

    public function getDateFacture(): ?\DateTimeInterface
    {
        return $this->date_facture;
    }

    public function setDateFacture(\DateTimeInterface $date_facture): static
    {
        $this->date_facture = $date_facture;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getTva(): ?TVA
    {
        return $this->tva;
    }

    public function setTva(?TVA $tva): static
    {
        $this->tva = $tva;

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

    /**
     * @return Collection<int, LigneFacture>
     */
    public function getLigneFactures(): Collection
    {
        return $this->ligneFactures;
    }

    public function addLigneFacture(LigneFacture $ligneFacture): static
    {
        if (!$this->ligneFactures->contains($ligneFacture)) {
            $this->ligneFactures->add($ligneFacture);
            $ligneFacture->setFacture($this);
        }

        return $this;
    }

    public function removeLigneFacture(LigneFacture $ligneFacture): static
    {
        if ($this->ligneFactures->removeElement($ligneFacture)) {
            // set the owning side to null (unless already changed)
            if ($ligneFacture->getFacture() === $this) {
                $ligneFacture->setFacture(null);
            }
        }

        return $this;
    }


    public function getTotalHT(): ?float
    {
        return $this->Total_HT;
    }

    public function setTotalHT(?float $Total_HT): static
    {
        $this->Total_HT = $Total_HT;

        return $this;
    }

    public function getTotalTVA(): ?float
    {
        return $this->Total_TVA;
    }

    public function setTotalTVA(?float $Total_TVA): static
    {
        $this->Total_TVA = $Total_TVA;

        return $this;
    }

    public function getTotalTTC(): ?float
    {
        return $this->Total_TTC;
    }

    public function setTotalTTC(?float $Total_TTC): static
    {
        $this->Total_TTC = $Total_TTC;

        return $this;
    }

    public function getTimbre(): ?float
    {
        return $this->timbre;
    }

    public function setTimbre(?float $timbre): static
    {
        $this->timbre = $timbre;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function isConfirmed(): ?bool
    {
        return $this->isConfirmed;
    }

    public function setConfirmed(?bool $isConfirmed): static
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    public function getPieceJoinRS(): ?string
    {
        return $this->pieceJoin_RS;
    }

    public function setPieceJoinRS(?string $pieceJoin_RS): static
    {
        $this->pieceJoin_RS = $pieceJoin_RS;

        return $this;
    }
}
