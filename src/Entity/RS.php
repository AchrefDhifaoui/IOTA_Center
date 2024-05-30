<?php

namespace App\Entity;

use App\Repository\RSRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RSRepository::class)]
class RS
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $taux = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateApplication = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaux(): ?int
    {
        return $this->taux;
    }

    public function setTaux(int $taux): static
    {
        $this->taux = $taux;

        return $this;
    }
    public function __toString(): string
    {
        return $this->taux.'%';
    }

    public function getDateApplication(): ?\DateTimeInterface
    {
        return $this->dateApplication;
    }

    public function setDateApplication(\DateTimeInterface $dateApplication): static
    {
        $this->dateApplication = $dateApplication;

        return $this;
    }


}
