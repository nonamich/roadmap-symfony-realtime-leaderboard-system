<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\Showtime;
use App\Entity\User;
use DateTime;
use DateTimeImmutable;
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

    /**
     * @return Reservation[]
     */
    public function findManyUpcoming(\DateInterval $interval): array
    {
        $now = new DateTimeImmutable();
        $feature = $now->add($interval);

        return $this->createQueryBuilder('r')
            ->innerJoin('r.showtime', 's')
            ->andWhere('s.startTime BETWEEN :now AND :feature')
            ->setParameter('now', $now)
            ->setParameter('now', $now)
            ->setParameter('feature', $feature)
            ->getQuery()
            ->getResult();
    }

    public function findOneOverlappingByCurrentUser(Showtime $showtime): mixed
    {
        $user = $this->security->getUser();

        if (!($user instanceof User)) {
            throw new AccessDeniedHttpException();
        }

        return $this->createQueryBuilder('r')
            ->innerJoin('r.showtime', 's')
            ->andWhere('s.startTime < :end')
            ->andWhere('s.endTime > :start')
            ->andWhere('r.customer = :user')
            ->andWhere('r.showtime != :currentShowtime')
            ->setParameter('user', $user->getId()->toBinary())
            ->setParameter('currentShowtime', $showtime)
            ->setParameter('end', $showtime->getStartTime())
            ->setParameter('start', $showtime->getEndTime())
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    /** {@inheritDoc} */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?Reservation
    {
        return parent::findOneBy($criteria, $orderBy);
    }
}
