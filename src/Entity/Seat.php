<?php

namespace App\Entity;

use App\Repository\SeatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeatRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_ROW_AND_COL_AND_HALL', fields: ['row', 'col', 'hall'])]
class Seat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 1)]
    private ?string $row = null;

    #[ORM\Column]
    private ?int $col = null;

    #[ORM\ManyToOne(inversedBy: 'seats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hall $hall = null;

    /**
     * @var Collection<int, ShowtimeSeat>
     */
    #[ORM\OneToMany(targetEntity: ShowtimeSeat::class, mappedBy: 'seat', orphanRemoval: true)]
    private Collection $showtimeSeats;

    public function __construct()
    {
        $this->showtimeSeats = new ArrayCollection();
    }

    public function __toString(): string
    {
        return "{$this->getCol()}:{$this->getRow()}";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRow(): ?string
    {
        return $this->row;
    }

    public function setRow(string $row): static
    {
        $this->row = $row;

        return $this;
    }

    public function getCol(): ?int
    {
        return $this->col;
    }

    public function setCol(int $col): static
    {
        $this->col = $col;

        return $this;
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

    /**
     * @return Collection<int, ShowtimeSeat>
     */
    public function getShowtimeSeats(): Collection
    {
        return $this->showtimeSeats;
    }

    public function addShowtimeSeat(ShowtimeSeat $showtimeSeat): static
    {
        if (!$this->showtimeSeats->contains($showtimeSeat)) {
            $this->showtimeSeats->add($showtimeSeat);
            $showtimeSeat->setSeat($this);
        }

        return $this;
    }

    public function removeShowtimeSeat(ShowtimeSeat $showtimeSeat): static
    {
        if ($this->showtimeSeats->removeElement($showtimeSeat)) {
            if ($showtimeSeat->getSeat() === $this) {
                $showtimeSeat->setSeat(null);
            }
        }

        return $this;
    }
}
