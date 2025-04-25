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
use App\Repository\ReservedSeatRepository;
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
        private ReservedSeatRepository $reservedSeatRepository,
        private Security $security,
        private RedisAdapter $cache,
    ) {
    }

    public function getReservationOrCreate(Showtime $showtime, User $user)
    {
        $reservation = $this->reservationRepository->findOneBy([
            'customer' => $user,
            'showtime' => $showtime
        ]);

        if (!$reservation) {
            $reservation = new Reservation();

            $reservation->setCustomer($user);
            $reservation->setShowtime($showtime);
        }

        return $reservation;
    }

    /**
     * @param ShowtimeSeat[] $selectedShowtimeSeats
     */
    public function reserveOrCancel(Showtime $showtime, User $user, array $selectedShowtimeSeats)
    {
        $this->isUpcomingOrThrow($showtime);

        $reservation = $this->getReservationOrCreate($showtime, $user);
        $reservedSeats = $reservation->getReservedSeats();
        $existingShowtimeSeats = $reservedSeats->map(
            fn($reservedSeat) => $reservedSeat->getShowtimeSeat()
        )->toArray();

        $showtimeSeatToCancel = array_diff($existingShowtimeSeats, $selectedShowtimeSeats);
        $showtimeSeatToReserve = array_diff($selectedShowtimeSeats, $existingShowtimeSeats);

        $this->entityManager->wrapInTransaction(function () use ($reservation, $showtimeSeatToCancel, $showtimeSeatToReserve) {
            foreach ($showtimeSeatToCancel as $showtimeSeat) {
                $reservedSeat = $showtimeSeat->getReservedSeat();

                $this->entityManager->remove($reservedSeat);
                $reservation->removeReservedSeat($reservedSeat);
            }

            $this->reserve($reservation, $showtimeSeatToReserve);

            $reservedSeatCount = $reservation->getReservedSeats()->count();

            if ($reservedSeatCount) {
                $this->mailer->send(
                    $this->emailReservationService->createOnReserve($reservation)
                );

                $this->entityManager->persist($reservation);
            } else {
                $this->mailer->send(
                    $this->emailReservationService->createOnCancel($reservation)
                );

                $this->entityManager->remove($reservation);
            }

            $this->entityManager->flush();
        });
    }

    /**
     * @param ShowtimeSeat[] $desiresShowtimeSeats
     */
    public function reserve(Reservation $reservation, array $desiresShowtimeSeats)
    {
        $this->isValidToReserveOrThrow($reservation->getShowtime(), $desiresShowtimeSeats);

        foreach ($desiresShowtimeSeats as $showtimeSeat) {
            $reservedSeat = new ReservedSeat();

            $reservedSeat->setShowtimeSeat($showtimeSeat);
            $reservation->addReservedSeat($reservedSeat);
            $this->entityManager->persist($reservedSeat);
        }
    }

    /**
     * @param ShowtimeSeat[] $desiresShowtimeSeats
     */
    public function isValidToReserveOrThrow(Showtime $showtime, array $desiresShowtimeSeats)
    {
        $this->isOverlappedOrThrow($showtime);
        $this->isSeatsFreeOrThrow($desiresShowtimeSeats);
    }

    public function isUpcomingOrThrow(Showtime $showtime)
    {
        if (!$showtime->isUpcoming()) {
            throw new ShowtimePassedException();
        }
    }

    /**
     * @param ShowtimeSeat[] $desiresShowtimeSeats
     */
    public function isSeatsFreeOrThrow(array $desiresShowtimeSeats)
    {
        if (!$this->isSeatsFree($desiresShowtimeSeats)) {
            throw new SeatTakenException();
        }
    }

    /**
     * @param ShowtimeSeat[] $desiresShowtimeSeats
     */
    public function isSeatsFree(array $desiresShowtimeSeats)
    {
        foreach ($desiresShowtimeSeats as $showtimeSeat) {
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
