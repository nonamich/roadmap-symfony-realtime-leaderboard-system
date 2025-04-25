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

    #[ORM\ManyToOne(inversedBy: 'reservedSeats', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'cascade')]
    private ?Reservation $reservation = null;

    #[ORM\OneToOne(inversedBy: 'reservedSeat')]
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

    public function getShowtimeSeat(): ShowtimeSeat
    {
        return $this->showtimeSeat;
    }

    public function setShowtimeSeat(ShowtimeSeat $showtimeSeat): static
    {
        $this->showtimeSeat = $showtimeSeat;

        return $this;
    }

    public function __toString(): string
    {
        return "{$this->getId()} Reserved Seat";
    }
}
