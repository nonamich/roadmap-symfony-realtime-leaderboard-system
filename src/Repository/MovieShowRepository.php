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

    public function findAllWithMovie()
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.movie', 'm')
            ->leftJoin('s.hall', 'h')
            ->addSelect('m')
            ->addSelect('h')
            ->getQuery()
            ->getResult();
    }
}
