<?php

namespace App\Repository;

use App\Entity\Showtime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Showtime>
 */
class ShowtimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Showtime::class);
    }

    /**
     * @return array<int, Showtime>
     */
    public function findAllRelations(): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.movie', 'm')
            ->leftJoin('s.hall', 'h')
            ->addSelect('m')
            ->addSelect('h')
            ->getQuery()
            ->getResult();
    }

    public function findOneRelations(int $id): ?Showtime
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.movie', 'm')
            ->leftJoin('s.hall', 'h')
            ->addSelect('m')
            ->addSelect('h')
            ->andWhere('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
