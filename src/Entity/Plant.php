<?php

namespace App\Entity;

use App\Repository\PlantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private ?\DateTime $dateOfPurchase = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $specialFeatures = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $dateOfLastWatering = null;

    #[ORM\OneToMany(mappedBy: 'plant', targetEntity: Watering::class)]
    private Collection $waterings;

    public function __construct()
    {
        $this->waterings = new ArrayCollection();
    }

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

    public function getWaterings(): Collection
    {
        return $this->waterings;
    }

    public function addWatering(Watering $watering): static
    {
        if (!$this->waterings->contains($watering)) {
            $this->waterings->add($watering);
            $watering->setPlant($this);
        }

        return $this;
    }

    public function removeWatering(Watering $watering): static
    {
        if ($this->waterings->removeElement($watering)) {
            // set the owning side to null (unless already changed)
            if ($watering->getPlant() === $this) {
                $watering->setPlant(null);
            }
        }

        return $this;
    }
}
