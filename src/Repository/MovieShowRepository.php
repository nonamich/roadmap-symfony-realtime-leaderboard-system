<?php

namespace App\Repository;

use App\Entity\MovieShow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MovieShow>
 */
class MovieShowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovieShow::class);
    }

    /**
     * @return array<int, MovieShow>
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

    public function findOneRelations(int $id): ?MovieShow
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
