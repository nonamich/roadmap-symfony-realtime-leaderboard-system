<?php

namespace App\Exception;

class ReservationTakenException extends \RuntimeException
{
    protected $message = 'out of time';
}
