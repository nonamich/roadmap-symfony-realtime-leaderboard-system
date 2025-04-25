<?php

namespace App\Exception;

class SeatTakenException extends BaseException
{
    protected $message = 'Some seats already taken';
}
