<?php
namespace App\Services;

use App\Entity\Reservation;
use App\Entity\ReservedSeat;
use App\Entity\Showtime;
use App\Entity\ShowtimeSeat;
use App\Entity\User;
use App\Exception\ReservationTakenException;
use App\Exception\SeatTakenException;
use App\Exception\ShowtimePassedException;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mailer\MailerInterface;

class ReservationService
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
        private ReservationRepository $reservationRepository,
        private MailerInterface $mailer,
        private EmailReservationService $emailReservationService,
    ) {
    }

    /**
     * @param ShowtimeSeat[] $showtimeSeats
     */
    public function reserve(Showtime $showtime, array $showtimeSeats)
    {
        $this->isValidOrThrow($showtime, $showtimeSeats);

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
        if ($showtime->isUpcoming()) {
            return true;
        }

        throw new ShowtimePassedException();
    }

    /**
     * @param ShowtimeSeat[] $showtimeSeats
     */
    public function isValidOrThrow(Showtime $showtime, array $showtimeSeats)
    {
        $this->isSeatsFreeOrThrow($showtimeSeats);
        $this->isUpcomingOrThrow($showtime);
    }

    /**
     * @param ShowtimeSeat[] $showtimeSeats
     */
    public function isSeatsFreeOrThrow(array $showtimeSeats)
    {
        if ($this->isSeatsFree($showtimeSeats)) {
            return true;
        }

        throw new SeatTakenException();
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

    public function isFree(Showtime $showtime)
    {
        $reservation = $this->reservationRepository->findOneBetweenByCurrentUser(
            $showtime->getStartTime(),
            $showtime->getEndTime()
        );

        if ($reservation) {
            throw new ReservationTakenException();
        }

        return true;
    }

    public function isFreeOrThrow(Showtime $showtime)
    {

    }

    /**
     * @param Reservation[] $reservations
     */
    public function remind(array $reservations)
    {
        foreach ($reservations as $reservation) {
            $mail = $this->emailReservationService->createRemind($reservation);

            $this->mailer->send($mail);
        }
    }
}
