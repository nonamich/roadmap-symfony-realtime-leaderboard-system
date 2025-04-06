<?php

namespace App\Entity;

use App\Repository\ReservedSeatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservedSeatRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_SEAT_AND_RESERVATION', fields: ['showtimeSeat', 'reservation'])]
class ReservedSeat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservedSeats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reservation $reservation = null;

    #[ORM\OneToOne(inversedBy: 'reversedSeat')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShowtimeSeat $showtimeSeat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }

    public function getShowtimeSeat(): ?ShowtimeSeat
    {
        return $this->showtimeSeat;
    }

    public function setShowtimeSeat(?ShowtimeSeat $showtimeSeat): static
    {
        // unset the owning side of the relation if necessary
        if ($showtimeSeat === null && $this->showtimeSeat !== null) {
            $this->showtimeSeat->setReversedSeat(null);
        }

        // set the owning side of the relation if necessary
        if ($showtimeSeat !== null && $showtimeSeat->getReversedSeat() !== $this) {
            $showtimeSeat->setReversedSeat($this);
        }

        $this->showtimeSeat = $showtimeSeat;

        return $this;
    }
}
