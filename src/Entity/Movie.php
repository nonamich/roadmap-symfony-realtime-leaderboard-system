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

    #[ORM\Column(length: 20)]
    private ?string $genre = null;

    #[ORM\Column]
    private ?string $poster = null;

    /**
     * @var Collection<int, MovieShow>
     */
    #[ORM\OneToMany(targetEntity: MovieShow::class, mappedBy: 'movie', orphanRemoval: true)]
    private Collection $shows;

    public function __construct()
    {
        $this->shows = new ArrayCollection();
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

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;

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
            $show->setMovie($this);
        }

        return $this;
    }

    public function removeShow(MovieShow $show): static
    {
        if ($this->shows->removeElement($show)) {
            // set the owning side to null (unless already changed)
            if ($show->getMovie() === $this) {
                $show->setMovie(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getTitle();
    }
}
