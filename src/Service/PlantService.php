<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Plant;
use Doctrine\ORM\EntityManagerInterface;

class PlantService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function delete(Plant $plant): void
    {
        $waterings = $plant->getWaterings();
        foreach ($waterings as $watering) {
            $this->entityManager->remove($watering);
        }
        $this->entityManager->remove($plant);
        $this->entityManager->flush();
    }
}
