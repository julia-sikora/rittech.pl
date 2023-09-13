<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Plant;
use App\Entity\User;
use App\Entity\Watering;
use App\Repository\PlantRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class WateringService
{
    public function __construct(private PlantRepository $plantRepository, private EntityManagerInterface $entityManager)
    {
    }

    public function water(Plant $plant): void
    {
        $watering = new Watering();
        $watering->setDate(new DateTime());
        $watering->setPlant($plant);
        $this->entityManager->persist($watering);
        $this->entityManager->flush();
    }

    public function waterAll(User $user): void
    {
        $plants = $this->plantRepository->findBy(['user' => $user]);
        foreach ($plants as $plant) {
            $watering = new Watering();
            $watering->setDate(new DateTime());
            $watering->setPlant($plant);
            $this->entityManager->persist($watering);
        }
        $this->entityManager->flush();
    }

    public function delete(Watering $watering): void
    {
        $this->entityManager->remove($watering);
        $this->entityManager->flush();
    }
}
