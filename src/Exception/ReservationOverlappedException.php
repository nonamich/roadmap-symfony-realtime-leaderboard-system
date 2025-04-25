<?php

namespace App\Exception;

class ReservationOverlappedException extends BaseException
{
    protected $message = 'Interval overlapping detected';
}
