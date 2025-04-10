<?php

namespace App\Exception;

class ShowtimePassedException extends \RuntimeException
{
    protected $message = 'Showtime is Passed';
}
