<?php

namespace App\Entity;

use App\Repository\PlantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlantRepository::class)]
class Plant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $species = null;

    #[ORM\Column(length: 255)]
    private ?string $variety = null;

    #[ORM\Column(nullable: true)]
    private ?bool $toxicity = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateOfPurchase = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $specialFeatures = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateOfLastWatering = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(string $species): static
    {
        $this->species = $species;

        return $this;
    }

    public function getVariety(): ?string
    {
        return $this->variety;
    }

    public function setVariety(string $variety): static
    {
        $this->variety = $variety;

        return $this;
    }

    public function isToxicity(): ?bool
    {
        return $this->toxicity;
    }

    public function setToxicity(?bool $toxicity): static
    {
        $this->toxicity = $toxicity;

        return $this;
    }

    public function getDateOfPurchase(): ?\DateTimeInterface
    {
        return $this->dateOfPurchase;
    }

    public function setDateOfPurchase(?\DateTimeInterface $dateOfPurchase): static
    {
        $this->dateOfPurchase = $dateOfPurchase;

        return $this;
    }

    public function getSpecialFeatures(): ?string
    {
        return $this->specialFeatures;
    }

    public function setSpecialFeatures(?string $specialFeatures): static
    {
        $this->specialFeatures = $specialFeatures;

        return $this;
    }

    public function getDateOfLastWatering(): ?\DateTimeInterface
    {
        return $this->dateOfLastWatering;
    }

    public function setDateOfLastWatering(?\DateTimeInterface $dateOfLastWatering): static
    {
        $this->dateOfLastWatering = $dateOfLastWatering;

        return $this;
    }
}
