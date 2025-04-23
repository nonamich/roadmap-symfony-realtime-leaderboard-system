<?php
namespace App\Services;

use App\Entity\Reservation;
use App\Entity\ReservedSeat;
use App\Entity\Showtime;
use App\Entity\ShowtimeSeat;
use App\Entity\User;
use App\Exception\ReservationOverlappedException;
use App\Exception\SeatTakenException;
use App\Exception\ShowtimePassedException;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ReservationService
{
    public function __construct(
        private EmailReservationService $emailReservationService,
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer,
        private ReservationRepository $reservationRepository,
        private Security $security,
        private RedisAdapter $cache,
    ) {
    }

    /**
     * @param ShowtimeSeat[] $showtimeSeats
     */
    public function reserve(Showtime $showtime, array $showtimeSeats)
    {
        $this->isValidOrThrow($showtime, $showtimeSeats);
        $this->createReservationAndSendEmail($showtime, $showtimeSeats);
    }

    /**
     * @param ShowtimeSeat[] $showtimeSeats
     */
    public function isValidOrThrow(Showtime $showtime, array $showtimeSeats)
    {
        $this->isUpcomingOrThrow($showtime);
        $this->isOverlappedOrThrow($showtime);
        $this->isSeatsFreeOrThrow($showtimeSeats);
    }

    private function createReservationAndSendEmail(Showtime $showtime, array $showtimeSeats)
    {
        $this->entityManager->wrapInTransaction(function () use ($showtime, $showtimeSeats) {
            $reservation = $this->createReservation($showtime, $showtimeSeats);

            $this->mailer->send(
                $this->emailReservationService->createOnReserve($reservation)
            );
        });
    }

    /**
     * @param ShowtimeSeat[] $showtimeSeats
     */
    public function createReservation(Showtime $showtime, array $showtimeSeats)
    {
        $reservation = $this->reservationRepository->findOneByCurrentUserOrCreate($showtime);

        foreach ($showtimeSeats as $showtimeSeat) {
            $reservedSeat = new ReservedSeat();

            $reservedSeat->setShowtimeSeat($showtimeSeat);
            $reservation->addReservedSeat($reservedSeat);
            $this->entityManager->persist($reservedSeat);
        }

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return $reservation;
    }

    public function isUpcomingOrThrow(Showtime $showtime)
    {
        if (!$showtime->isUpcoming()) {
            throw new ShowtimePassedException();
        }
    }

    /**
     * @param ShowtimeSeat[] $showtimeSeats
     */
    public function isSeatsFreeOrThrow(array $showtimeSeats)
    {
        if (!$this->isSeatsFree($showtimeSeats)) {
            throw new SeatTakenException();
        }
    }

    /**
     * @param ShowtimeSeat[] $showtimeSeats
     */
    public function isSeatsFree(array $showtimeSeats)
    {
        foreach ($showtimeSeats as $showtimeSeat) {
            if ($showtimeSeat->getReservedSeat()) {
                return false;
            }
        }

        return true;
    }

    public function isOverlapped(Showtime $showtime)
    {
        $reservation = $this->reservationRepository->findOneOverlappingByCurrentUser($showtime);

        return !$reservation;
    }

    public function isOverlappedOrThrow(Showtime $showtime)
    {
        if (!$this->isOverlapped($showtime)) {
            throw new ReservationOverlappedException();
        }
    }

    /**
     * @param Reservation[] $reservations
     */
    public function remind(array $reservations)
    {
        foreach ($reservations as $reservation) {
            $cacheReminded = $this->cache->getItem(
                "reservation.reminded.{$reservation->getId()}"
            );

            if ($cacheReminded->isHit()) {
                continue;
            }

            $mail = $this->emailReservationService->createRemind($reservation);

            $this->mailer->send($mail);

            $cacheReminded->expiresAfter(\DateInterval::createFromDateString('2 hours'));
            $cacheReminded->set(true);

            $this->cache->save($cacheReminded);
        }
    }
}
