<?php

namespace App\Entity;

use App\Repository\ActorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActorRepository::class)]
class Actor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'date', nullable: true)]
    private $date_born;

    #[ORM\Column(type: 'date', nullable: true)]
    private $date_dead;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $place_born;

    #[ORM\ManyToMany(targetEntity: Movie::class, mappedBy: 'actors')]
    private $movies;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDateBorn(): ?\DateTimeInterface
    {
        return $this->date_born;
    }

    public function setDateBorn(?\DateTimeInterface $date_born): self
    {
        $this->date_born = $date_born;

        return $this;
    }

    public function getDateDead(): ?\DateTimeInterface
    {
        return $this->date_dead;
    }

    public function setDateDead(?\DateTimeInterface $date_dead): self
    {
        $this->date_dead = $date_dead;

        return $this;
    }

    public function getPlaceBorn(): ?string
    {
        return $this->place_born;
    }

    public function setPlaceBorn(?string $place_born): self
    {
        $this->place_born = $place_born;

        return $this;
    }

    /**
     * @return Collection<int, Movie>
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): self
    {
        if (!$this->movies->contains($movie)) {
            $this->movies[] = $movie;
            $movie->addActor($this);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): self
    {
        if ($this->movies->removeElement($movie)) {
            $movie->removeActor($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
