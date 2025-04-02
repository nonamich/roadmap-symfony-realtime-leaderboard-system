<?php

namespace App\Entity;

use App\Repository\ReservedSeatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservedSeatRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_SEAT_AND_RESERVATION', fields: ['seat', 'reservation'])]
class ReservedSeat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Seat $seat = null;

    #[ORM\ManyToOne(inversedBy: 'reservedSeats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reservation $reservation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeat(): ?Seat
    {
        return $this->seat;
    }

    public function setSeat(Seat $seat): static
    {
        $this->seat = $seat;

        return $this;
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
}
