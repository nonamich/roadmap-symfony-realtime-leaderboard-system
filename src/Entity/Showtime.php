<?php

namespace App\Entity;

use App\Repository\ShowtimeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShowtimeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Showtime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endTime = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdOn = null;

    #[ORM\ManyToOne(inversedBy: 'showtimes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hall $hall = null;

    #[ORM\ManyToOne(inversedBy: 'showtimes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Movie $movie = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'showtime')]
    private Collection $reservations;

    /**
     * @var Collection<int, ShowtimeSeat>
     */
    #[ORM\OneToMany(targetEntity: ShowtimeSeat::class, mappedBy: 'showtime', orphanRemoval: true)]
    private Collection $showtimeSeats;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->showtimeSeats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->createdOn;
    }

    public function setCreatedOn(\DateTimeInterface $createdOn): static
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    #[ORM\PrePersist]
    public function updatedTimestamps() {
        $this->setCreatedOn(new \DateTimeImmutable('now'));
    }

    public function getHall(): ?Hall
    {
        return $this->hall;
    }

    public function setHall(?Hall $hall): static
    {
        $this->hall = $hall;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): static
    {
        $this->movie = $movie;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setShowtime($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            if ($reservation->getShowtime() === $this) {
                $reservation->setShowtime(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ShowtimeSeat>
     */
    public function getShowtimeSeats(): Collection
    {
        return $this->showtimeSeats;
    }

    public function addShowSeat(ShowtimeSeat $showSeat): static
    {
        if (!$this->showtimeSeats->contains($showSeat)) {
            $this->showtimeSeats->add($showSeat);
            $showSeat->setShowtime($this);
        }

        return $this;
    }

    public function removeShowSeat(ShowtimeSeat $showSeat): static
    {
        if ($this->showtimeSeats->removeElement($showSeat)) {
            if ($showSeat->getMovieShow() === $this) {
                $showSeat->setShowtime(null);
            }
        }

        return $this;
    }
}
