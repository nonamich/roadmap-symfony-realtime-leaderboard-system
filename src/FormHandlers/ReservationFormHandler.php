<?php

namespace App\FormHandlers;

use App\Entity\Reservation;
use App\Entity\Showtime;
use App\Entity\User;
use App\Services\ReservationService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Entity\ShowtimeSeat;

class ReservationFormHandler
{
    public function __construct(
        private ReservationService $reservationService,
        private AuthorizationCheckerInterface $authChecker,
    ) {
    }

    public function handlerForm(FormInterface $form, Showtime $showtime, User $user): Reservation
    {
        /**
         * @var ShowtimeSeat[]
         */
        $seats = $form->get('seats')->getData();

        return $this->reservationService->reserveOrCancel(
            $showtime,
            $user,
            $seats
        );
    }
}
