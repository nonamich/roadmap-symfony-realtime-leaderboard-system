<?php

namespace App\FormHandlers;

use App\Entity\Showtime;
use App\Entity\User;
use App\Exception\BaseException;
use App\Services\ReservationService;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Form\FormError;
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

    public function handler(FormInterface $form, Showtime $showtime, User $user)
    {
        if (!$form->isSubmitted() || !$form->isValid()) {
            return;
        }

        /**
         * @var ShowtimeSeat[]
         */
        $seats = $form->get('seats')->getData();

        try {
            $this->reservationService->reserveOrCancel(
                $showtime,
                $user,
                $seats
            );
        } catch (BaseException $exception) {
            $form->addError(new FormError($exception->getMessage()));
        }
    }
}
