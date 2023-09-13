<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\PlantPotRepository;
use Doctrine\ORM\EntityManagerInterface;

class PlantPotService
{
    public function __construct(private EntityManagerInterface $entityManager, private PlantPotRepository $plantPotRepository)
    {
    }

    public function delete($id): void
    {
        $plantPot = $this->plantPotRepository->find($id);
        $plant = $plantPot->getPlant();
        if ($plant != null) {
            $plant->setPlantPot(null);
            $this->entityManager->persist($plant);
        }
        $this->entityManager->remove($plantPot);
        $this->entityManager->flush();
    }
}
