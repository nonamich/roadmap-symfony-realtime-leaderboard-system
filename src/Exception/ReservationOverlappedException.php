<?php

namespace App\Exception;

class ReservationOverlappedException extends \RuntimeException
{
    protected $message = 'Interval overlapping detected';
}
