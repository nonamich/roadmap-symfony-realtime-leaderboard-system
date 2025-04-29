<?php
namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function findOneAndShowtimes(int $id): ?Movie
    {
        return $this->createQueryBuilder('m')
            ->innerJoin('m.showtimes', 's')
            ->innerJoin('s.hall', 'h')
            ->innerJoin('s.showtimeSeats', 'ss')
            ->leftJoin('ss.reservedSeat', 'rs')
            ->addSelect('s')
            ->addSelect('ss')
            ->addSelect('h')
            ->addSelect('rs')
            ->andWhere('m.id = :id')
            ->setParameter('id', $id)
            ->orderBy('s.startTime', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findHomepage()
    {
        return $this->createQueryBuilder('m')
            ->innerJoin('m.showtimes', 's')
            ->andWhere('s.startTime > CURRENT_TIMESTAMP()')
            ->orderBy('s.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
