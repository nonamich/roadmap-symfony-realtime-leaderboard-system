<?php

namespace App\Form\Handlers;

use App\Entity\Showtime;
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

    public function handleCreate(FormInterface $form, Showtime $showtime)
    {
        if (!$form->isSubmitted() || !$form->isValid()) {
            return;
        }

        if (!$this->authChecker->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        /**
         * @var ShowtimeSeat[]
         */
        $showtimeSeats = $form->get('showtimeSeats')->getData();

        try {
            $this->reservationService->reserve(
                $showtime,
                $showtimeSeats
            );
        } catch (\Throwable $th) {
            $form->addError(new FormError($th->getMessage()));
        }
    }
}
