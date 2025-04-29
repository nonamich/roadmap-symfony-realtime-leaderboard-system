<?php

namespace App\Services;

use App\Entity\Reservation;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailReservationService
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    public function notifyReservation(Reservation $reservation)
    {
        $email = (new TemplatedEmail())
            ->context([
                'reservation' => $reservation
            ])
            ->to($reservation->getCustomer()->getEmail())
            ->subject("Reservation #{$reservation->getTicketCode()}")
            ->htmlTemplate('email/reservation.html.twig');

        $this->mailer->send($email);
    }
    public function notifyCancellation(Reservation $reservation)
    {
        $email = (new TemplatedEmail())
            ->context([
                'reservation' => $reservation
            ])
            ->to($reservation->getCustomer()->getEmail())
            ->subject("Reservation cancellation #{$reservation->getTicketCode()}")
            ->htmlTemplate('email/reservation-cancellation.html.twig');

        $this->mailer->send($email);
    }

    public function notifyRemind(Reservation $reservation)
    {
        $email = (new TemplatedEmail())
            ->context([
                'reservation' => $reservation
            ])
            ->to($reservation->getCustomer()->getEmail())
            ->subject("Remind {$reservation->getTicketCode()}")
            ->htmlTemplate('email/remind.html.twig');

        $this->mailer->send($email);
    }
}
