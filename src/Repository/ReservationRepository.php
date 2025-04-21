<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\Showtime;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private Security $security
    ) {
        parent::__construct($registry, Reservation::class);
    }

    public function findOneByCurrentUserOrCreate(Showtime $showtime): Reservation
    {
        $user = $this->security->getUser();

        if (!($user instanceof User)) {
            throw new AccessDeniedHttpException();
        }

        $reservation = $this->findOneBy([
            'customer' => $user,
            'showtime' => $showtime,
        ]);

        if (!$reservation) {
            $reservation = new Reservation();

            $reservation->setCustomer($user);
            $reservation->setShowtime($showtime);
        }

        return $reservation;
    }

    /**
     * @return Reservation[]
     */
    public function findOneBetween(\DateTimeInterface $start, \DateTimeInterface $end): array
    {
        return $this->createFindOneBetween($start, $end)->getQuery()->getResult();
    }

    public function findOneOverlappingByCurrentUser(Showtime $showtime): mixed
    {
        $user = $this->security->getUser();

        if (!($user instanceof User)) {
            throw new AccessDeniedHttpException();
        }

        return $this->createFindOneBetween(
            $showtime->getStartTime(),
            $showtime->getEndTime()
        )
            ->andWhere('r.customer = :user')
            ->andWhere('r.showtime != :currentShowtime')
            ->setParameter('user', $user->getId()->toBinary())
            ->setParameter('currentShowtime', $showtime)
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    private function createFindOneBetween(\DateTimeInterface $start, \DateTimeInterface $end)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.showtime', 's')
            ->andWhere('s.startTime < :end')
            ->andWhere('s.endTime > :start')
            ->setParameter('end', $end)
            ->setParameter('start', $start);
    }
}
