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
        $start = new \DateTimeImmutable();
        $end = $start->add($message->dateinterval);
        $reservations = $this->repository->findOneBetween($start, $end);

        $this->service->remind($reservations);
    }
}
