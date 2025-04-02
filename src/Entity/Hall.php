<?php

namespace App\Entity;

use App\Repository\HallRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HallRepository::class)]
class Hall
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    private ?string $name = null;

    /**
     * @var Collection<int, Seat>
     */
    #[ORM\OneToMany(targetEntity: Seat::class, mappedBy: 'hall', orphanRemoval: true)]
    #[ORM\OrderBy(['row' => 'ASC', 'col' => 'ASC'])]
    private Collection $seats;

    /**
     * @var Collection<int, Showtime>
     */
    #[ORM\OneToMany(targetEntity: Showtime::class, mappedBy: 'hall', orphanRemoval: true)]
    private Collection $showtimes;

    public function __construct()
    {
        $this->seats = new ArrayCollection();
        $this->shows = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Seat>
     */
    public function getSeats(): Collection
    {
        return $this->seats;
    }

    public function addSeat(Seat $seat): static
    {
        if (!$this->seats->contains($seat)) {
            $this->seats->add($seat);
            $seat->setHall($this);
        }

        return $this;
    }

    public function removeSeat(Seat $seat): static
    {
        if ($this->seats->removeElement($seat)) {
            // set the owning side to null (unless already changed)
            if ($seat->getHall() === $this) {
                $seat->setHall(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Showtime>
     */
    public function getShowtimes(): Collection
    {
        return $this->showtimes;
    }

    public function addShow(Showtime $showtime): static
    {
        if (!$this->showtimes->contains($showtime)) {
            $this->showtimes->add($showtime);
            $showtime->setHall($this);
        }

        return $this;
    }

    public function removeShow(Showtime $showtime): static
    {
        if ($this->showtimes->removeElement($showtime)) {
            if ($showtime->getHall() === $this) {
                $showtime->setHall(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
