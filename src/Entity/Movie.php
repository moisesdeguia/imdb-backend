<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'date', nullable: true)]
    private $date_release;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private $duration;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $production_company;

    #[ORM\ManyToMany(targetEntity: Actor::class, inversedBy: 'movies')]
    private $actors;

    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'movies')]
    private $genres;

    #[ORM\ManyToMany(targetEntity: Director::class, inversedBy: 'movies')]
    private $directors;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private $imdb_id;

    public function __construct()
    {
        $this->actors = new ArrayCollection();
        $this->genres = new ArrayCollection();
        $this->directors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDateRelease(): ?\DateTimeInterface
    {
        return $this->date_release;
    }

    public function setDateRelease(?\DateTimeInterface $date_release): self
    {
        $this->date_release = $date_release;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getProductionCompany(): ?string
    {
        return $this->production_company;
    }

    public function setProductionCompany(?string $production_company): self
    {
        $this->production_company = $production_company;

        return $this;
    }

    /**
     * @return Collection<int, Actor>
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function addActor(Actor $actor): self
    {
        if (!$this->actors->contains($actor)) {
            $this->actors[] = $actor;
        }

        return $this;
    }

    public function removeActor(Actor $actor): self
    {
        $this->actors->removeElement($actor);

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    /**
     * @return Collection<int, Director>
     */
    public function getDirectors(): Collection
    {
        return $this->directors;
    }

    public function addDirector(Director $director): self
    {
        if (!$this->directors->contains($director)) {
            $this->directors[] = $director;
        }

        return $this;
    }

    public function removeDirector(Director $director): self
    {
        $this->directors->removeElement($director);

        return $this;
    }

    public function getImdbId(): ?string
    {
        return $this->imdb_id;
    }

    public function setImdbId(string $imdb_id): self
    {
        $this->imdb_id = $imdb_id;

        return $this;
    }
}
