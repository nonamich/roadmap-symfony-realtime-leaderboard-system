<?php

namespace App\MessageHandler;

use App\Message\RemindReservationsMessage;
use App\Repository\ReservationRepository;
use App\Services\ReservationService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class RemindReservationsMessageHandler
{
    public function __construct(
        private ReservationService $service,
        private ReservationRepository $repository
    ) {
    }

    public function __invoke(RemindReservationsMessage $message): void
    {
        $reservations = $this->repository->findManyUpcoming($message->dateinterval);

        $this->service->remind($reservations);
    }
}
