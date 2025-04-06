<?php
namespace App\Services;

use App\Entity\Showtime;
use App\Entity\ShowtimeSeat;

class ReservationService
{
    /**
     * @param ShowtimeSeat[] $showtimeSeats
     */
    public function reserve(Showtime $showtime, array $showtimeSeats) {

    }

    public function isShowtimeOnTime(Showtime $showtime) {
        return new \DateTime() < $showtime->getStartTime();
    }

    public function isShowtimeValid(Showtime $showtime) {
        return $this->isShowtimeOnTime($showtime);
    }

    /**
     * @param ShowtimeSeat[] $showtimeSeats
     */
    public function isSeatsFree(array $showtimeSeats) {
        // $showtimeSeats[0]->

        return array_map(function(ShowtimeSeat $showtimeSeat) {
            return $showtimeSeat->getReversedSeat();
        }, $showtimeSeats);
    }
}
