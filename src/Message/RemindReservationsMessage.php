<?php

namespace App\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
final class RemindReservationsMessage
{
    public function __construct(
        public readonly \DateInterval $dateinterval,
    ) {
    }
}
