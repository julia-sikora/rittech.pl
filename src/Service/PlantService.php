<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Plant;
use App\Entity\Watering;
use App\Repository\PlantRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class PlantService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {}
    public function water(Plant $plant)
    {
        $plant->setDateOfLastWatering(new DateTime());
        $this->entityManager->persist($plant);

        $watering = new Watering();
        $watering->setDate(new DateTime());
        $watering->setPlant($plant);
        $this->entityManager->persist($watering);

        $this->entityManager->flush();
    }
}
