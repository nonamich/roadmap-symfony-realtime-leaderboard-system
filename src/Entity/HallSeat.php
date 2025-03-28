<?php

namespace App\Entity;

use App\Repository\HallSeatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HallSeatRepository::class)]
class HallSeat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $row = null;

    #[ORM\Column]
    private ?int $col = null;

    #[ORM\ManyToOne(inversedBy: 'seats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hall $hall = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRow(): ?int
    {
        return $this->row;
    }

    public function setRow(int $row): static
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

    public function __toString(): string
    {
        return "{$this->getCol()}:{$this->getRow()}";
    }
}
