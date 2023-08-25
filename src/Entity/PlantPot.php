<?php

namespace App\Entity;

use App\Repository\PlantPotRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlantPotRepository::class)]
class PlantPot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $colour = null;

    #[ORM\Column(length: 255)]
    private ?string $producer = null;

    #[ORM\Column(length: 255)]
    private ?string $potCode = null;

    #[ORM\Column]
    private ?float $potDiameter = null;

    #[ORM\OneToOne(mappedBy: 'plantPot', cascade: ['persist', 'remove'])]
    private ?Plant $plant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColour(): ?string
    {
        return $this->colour;
    }

    public function setColour(string $colour): static
    {
        $this->colour = $colour;

        return $this;
    }

    public function getProducer(): ?string
    {
        return $this->producer;
    }

    public function setProducer(string $producer): static
    {
        $this->producer = $producer;

        return $this;
    }

    public function getPotCode(): ?string
    {
        return $this->potCode;
    }

    public function setPotCode(string $potCode): static
    {
        $this->potCode = $potCode;

        return $this;
    }

    public function getPotDiameter(): ?float
    {
        return $this->potDiameter;
    }

    public function setPotDiameter(float $potDiameter): static
    {
        $this->potDiameter = $potDiameter;

        return $this;
    }

    public function getPlant(): ?Plant
    {
        return $this->plant;
    }

    public function setPlant(?Plant $plant): static
    {
        // unset the owning side of the relation if necessary
        if ($plant === null && $this->plant !== null) {
            $this->plant->setPlantPot(null);
        }

        // set the owning side of the relation if necessary
        if ($plant !== null && $plant->getPlantPot() !== $this) {
            $plant->setPlantPot($this);
        }

        $this->plant = $plant;

        return $this;
    }
}