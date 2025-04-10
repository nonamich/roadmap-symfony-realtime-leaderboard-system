<?php

namespace App\Exception;

class SeatTakenException extends \RuntimeException
{
    protected $message = 'Some seats already taken';
}
