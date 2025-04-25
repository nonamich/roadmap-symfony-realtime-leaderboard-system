<?php

namespace App\Services;

use App\Entity\Reservation;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class EmailReservationService
{
    public function createOnReserve(Reservation $reservation)
    {
        return (new TemplatedEmail())
            ->context([
                'reservation' => $reservation
            ])
            ->to($reservation->getCustomer()->getEmail())
            ->subject("Reservation #{$reservation->getTicketCode()}")
            ->htmlTemplate('email/reservation.html.twig');
    }
    public function createOnCancel(Reservation $reservation)
    {
        return (new TemplatedEmail())
            ->context([
                'reservation' => $reservation
            ])
            ->to($reservation->getCustomer()->getEmail())
            ->subject("Reservation cancellation #{$reservation->getTicketCode()}")
            ->htmlTemplate('email/reservation-cancellation.html.twig');
    }

    public function createRemind(Reservation $reservation) {
        return (new TemplatedEmail())
            ->context([
                'reservation' => $reservation
            ])
            ->to($reservation->getCustomer()->getEmail())
            ->subject("Remind {$reservation->getTicketCode()}")
            ->htmlTemplate('email/remind.html.twig');
    }
}
