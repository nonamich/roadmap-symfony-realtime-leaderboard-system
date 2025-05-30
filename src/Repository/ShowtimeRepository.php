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

    public function findOneMovieAndSeats(int $id): ?Showtime
    {
        return $this->createQueryBuilder('showtime')
            ->innerJoin('showtime.movie', 'movie')
            ->innerJoin('showtime.showtimeSeats', 'showtimeSeats')
            ->innerJoin('showtimeSeats.seat', 'seat')
            ->leftJoin('showtimeSeats.reservedSeat', 'reservedSeat')
            ->addSelect('movie')
            ->addSelect('showtimeSeats')
            ->addSelect('seat')
            ->addSelect('reservedSeat')
            ->andWhere('showtime.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
