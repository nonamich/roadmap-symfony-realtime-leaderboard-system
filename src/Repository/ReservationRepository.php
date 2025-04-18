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
    public function findOneBetween(\DateTimeImmutable $start, \DateTimeImmutable $end): array
    {
        return $this->createFindOneBetween($start, $end)->getQuery()->getResult();
    }

    public function findOneBetweenByCurrentUser(\DateTimeImmutable $start, \DateTimeImmutable $end): ?Reservation
    {
        $user = $this->security->getUser();

        if (!($user instanceof User)) {
            throw new AccessDeniedHttpException();
        }

        return $this->createFindOneBetween($start, $end)
            ->innerJoin('r.customer', 'c')
            ->andWhere('r.customer.id = :customerId')
            ->setParameter('customerId', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function createFindOneBetween(\DateTimeImmutable $start, \DateTimeImmutable $end)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.showtime', 's')
            ->andWhere('s.startTime BETWEEN :start AND :end')
            ->setParameter('end', $end)
            ->setParameter('start', $start);
    }
}
