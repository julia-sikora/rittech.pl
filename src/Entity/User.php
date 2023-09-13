<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $messages;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Plant::class, orphanRemoval: true)]
    private Collection $plant;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: PlantPot::class, orphanRemoval: true)]
    private Collection $plantPot;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->plant = new ArrayCollection();
        $this->plantPot = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /** @return Collection<int, Message> */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    /** @return Collection<int, Plant> */
    public function getPlant(): Collection
    {
        return $this->plant;
    }

    public function addPlant(Plant $plant): static
    {
        if (!$this->plant->contains($plant)) {
            $this->plant->add($plant);
            $plant->setUser($this);
        }

        return $this;
    }

    public function removePlant(Plant $plant): static
    {
        if ($this->plant->removeElement($plant)) {
            if ($plant->getUser() === $this) {
                $plant->setUser(null);
            }
        }

        return $this;
    }

    /** @return Collection<int, PlantPot> */
    public function getPlantPot(): Collection
    {
        return $this->plantPot;
    }

    public function addPlantPot(PlantPot $plantPot): static
    {
        if (!$this->plantPot->contains($plantPot)) {
            $this->plantPot->add($plantPot);
            $plantPot->setUser($this);
        }

        return $this;
    }

    public function removePlantPot(PlantPot $plantPot): static
    {
        if ($this->plantPot->removeElement($plantPot)) {
            if ($plantPot->getUser() === $this) {
                $plantPot->setUser(null);
            }
        }

        return $this;
    }
}
