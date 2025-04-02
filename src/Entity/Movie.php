<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    private ?string $title = null;

    #[ORM\Column]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $durationInMins = null;

    #[ORM\Column(length: 4)]
    private ?string $language = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $releaseDate = null;

    #[ORM\Column(length: 2)]
    private ?string $country = null;

    /**
     * @var list<string>
     */
    #[ORM\Column]
    private array $genres = [];

    #[ORM\Column]
    private ?string $poster = null;

    /**
     * @var Collection<int, Showtime>
     */
    #[ORM\OneToMany(targetEntity: Showtime::class, mappedBy: 'movie', orphanRemoval: true)]
    private Collection $showtimes;

    public function __construct()
    {
        $this->showtimes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDurationInMins(): ?int
    {
        return $this->durationInMins;
    }

    public function setDurationInMins(int $durationInMins): static
    {
        $this->durationInMins = $durationInMins;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return list<string>
     */
    public function getGenres(): ?array
    {
        return array_unique($this->genres);
    }

    /**
     * @param list<string> $genres
     */
    public function setGenres(array $genres): static
    {
        $this->genres = $genres;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): static
    {
        $this->poster = $poster;

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
            $showtime->setMovie($this);
        }

        return $this;
    }

    public function removeShow(Showtime $showtime): static
    {
        if ($this->showtimes->removeElement($showtime)) {
            if ($showtime->getMovie() === $this) {
                $showtime->setMovie(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getTitle();
    }
}
