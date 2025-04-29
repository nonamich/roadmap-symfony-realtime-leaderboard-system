<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case Ok = 'ok';
    case Canceled = 'canceled';
}
