<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_CUSTOMER_AND_SHOWTIME', fields: ['customer', 'showtime'])]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $customer = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Showtime $showtime = null;

    /**
     * @var Collection<int, ReservedSeat>
     */
    #[ORM\OneToMany(targetEntity: ReservedSeat::class, mappedBy: 'reservation')]
    private Collection $reservedSeats;

    public function __construct()
    {
        $this->reservedSeats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getShow(): ?Showtime
    {
        return $this->showtime;
    }

    public function setShow(?Showtime $showtime): static
    {
        $this->showtime = $showtime;

        return $this;
    }

    /**
     * @return Collection<int, ReservedSeat>
     */
    public function getReservedSeats(): Collection
    {
        return $this->reservedSeats;
    }

    public function addReservedSeat(ReservedSeat $reservationSeat): static
    {
        if (!$this->reservedSeats->contains($reservationSeat)) {
            $this->reservedSeats->add($reservationSeat);
            $reservationSeat->setReservation($this);
        }

        return $this;
    }

    public function removeReservedSeat(ReservedSeat $reservationSeat): static
    {
        if ($this->reservedSeats->removeElement($reservationSeat)) {
            if ($reservationSeat->getReservation() === $this) {
                $reservationSeat->setReservation(null);
            }
        }

        return $this;
    }
}
