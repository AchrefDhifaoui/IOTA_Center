<?php

namespace App\Entity;

use App\Repository\TimbreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimbreRepository::class)]
class Timbre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $taux = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_application = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaux(): ?float
    {
        return $this->taux;
    }

    public function setTaux(float $taux): static
    {
        $this->taux = $taux;

        return $this;
    }

    public function getDateApplication(): ?\DateTimeInterface
    {
        return $this->date_application;
    }

    public function setDateApplication(\DateTimeInterface $date_application): static
    {
        $this->date_application = $date_application;

        return $this;
    }

    public function __toString(): string
    {
        $dateString = $this->date_application ? $this->date_application->format('d-m-Y') : 'No Date';
        return $this->taux.' DT  (' . $dateString . ')';
    }

}
