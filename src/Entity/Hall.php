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
     * @var Collection<int, HallSeat>
     */
    #[ORM\OneToMany(targetEntity: HallSeat::class, mappedBy: 'hall', orphanRemoval: true)]
    private Collection $seats;

    /**
     * @var Collection<int, MovieShow>
     */
    #[ORM\OneToMany(targetEntity: MovieShow::class, mappedBy: 'hall', orphanRemoval: true)]
    private Collection $shows;

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
     * @return Collection<int, HallSeat>
     */
    public function getSeats(): Collection
    {
        return $this->seats;
    }

    public function addSeat(HallSeat $seat): static
    {
        if (!$this->seats->contains($seat)) {
            $this->seats->add($seat);
            $seat->setHall($this);
        }

        return $this;
    }

    public function removeSeat(HallSeat $seat): static
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
     * @return Collection<int, MovieShow>
     */
    public function getShows(): Collection
    {
        return $this->shows;
    }

    public function addShow(MovieShow $show): static
    {
        if (!$this->shows->contains($show)) {
            $this->shows->add($show);
            $show->setHall($this);
        }

        return $this;
    }

    public function removeShow(MovieShow $show): static
    {
        if ($this->shows->removeElement($show)) {
            // set the owning side to null (unless already changed)
            if ($show->getHall() === $this) {
                $show->setHall(null);
            }
        }

        return $this;
    }
}
