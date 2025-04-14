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

    public function findToRemind(): Reservation
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.showtime', 's')
            ->andWhere('s.startTime > CURRENT_TIMESTAMP()')
            ->getQuery()
            ->getResult();
    }
}
