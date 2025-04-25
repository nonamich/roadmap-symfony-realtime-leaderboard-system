<?php

namespace App\Entity;

use App\Repository\ShowtimeSeatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShowtimeSeatRepository::class)]
class ShowtimeSeat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'showtimeSeats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Seat $seat = null;

    #[ORM\Column]
    private ?int $priceInCents = null;

    #[ORM\ManyToOne(inversedBy: 'showtimeSeats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Showtime $showtime = null;

    #[ORM\OneToOne(mappedBy: 'showtimeSeat')]
    private ?ReservedSeat $reservedSeat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeat(): ?Seat
    {
        return $this->seat;
    }

    public function setSeat(?Seat $seat): static
    {
        $this->seat = $seat;

        return $this;
    }

    public function getPriceInCents(): ?int
    {
        return $this->priceInCents;
    }

    public function setPriceInCents(int $priceInCents): static
    {
        $this->priceInCents = $priceInCents;

        return $this;
    }

    public function getMovieShow(): ?Showtime
    {
        return $this->showtime;
    }

    public function setShowtime(?Showtime $showtime): static
    {
        $this->showtime = $showtime;

        return $this;
    }

    public function getReservedSeat(): ?ReservedSeat
    {
        return $this->reservedSeat;
    }

    public function setReservedSeat(ReservedSeat $reservedSeat): static
    {
        // set the owning side of the relation if necessary
        if ($reservedSeat->getShowtimeSeat() !== $this) {
            $reservedSeat->setShowtimeSeat($this);
        }

        $this->reservedSeat = $reservedSeat;

        return $this;
    }

    public function __toString(): string
    {
        return "#{$this->getId()} {$this->getSeat()->getRow()}:{$this->getSeat()->getCol()}";
    }
}
