<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_CUSTOMER_AND_SHOWTIME', fields: ['customer', 'showtime'])]
#[ORM\HasLifecycleCallbacks]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
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

    #[ORM\Column(type: 'uuid')]
    private ?Uuid $ticketCode = null;

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

    public function getShowtime(): ?Showtime
    {
        return $this->showtime;
    }

    public function setShowtime(?Showtime $showtime): static
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

    public function getTicketCode(): ?Uuid
    {
        return $this->ticketCode;
    }

    public function setTicketCode(Uuid $ticketCode): static
    {
        $this->ticketCode = $ticketCode;

        return $this;
    }

    #[ORM\PrePersist]
    public function generateTicketCode(): void
    {
        if ($this->ticketCode === null) {
            $this->ticketCode = Uuid::v7();
        }
    }

    public function __toString(): string
    {
        return "#{$this->getId()} Reservation";
    }
}
