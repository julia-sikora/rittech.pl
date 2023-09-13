<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PlantPot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlantPot>
 *
 * @method PlantPot|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlantPot|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlantPot[]    findAll()
 * @method PlantPot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlantPotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlantPot::class);
    }
}
