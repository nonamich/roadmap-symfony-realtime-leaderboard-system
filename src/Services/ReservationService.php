<?php
namespace App\Services;

use App\Entity\Reservation;
use App\Entity\ReservedSeat;
use App\Entity\Showtime;
use App\Entity\ShowtimeSeat;
use App\Entity\User;
use App\Exception\SeatTakenException;
use App\Exception\ShowtimePassedException;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

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
        $this->isValidReservationOrThrow($showtime, $showtimeSeats);

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

    public function isShowtimeOnTimeOrThrow(Showtime $showtime)
    {
        if ($this->isShowtimeOnTime($showtime)) {
            return true;
        }

        throw new ShowtimePassedException();
    }

    public function isShowtimeOnTime(Showtime $showtime)
    {
        return new \DateTime() < $showtime->getStartTime();
    }

    /**
     * @param ShowtimeSeat[] $showtimeSeats
     */
    public function isValidReservationOrThrow(Showtime $showtime, array $showtimeSeats)
    {
        $this->isSeatsFreeOrThrow($showtimeSeats);
        $this->isShowtimeOnTimeOrThrow($showtime);
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
        return array_all($showtimeSeats, function (ShowtimeSeat $showtimeSeat) {
            return !$showtimeSeat->getReservedSeat();
        });
    }
}
