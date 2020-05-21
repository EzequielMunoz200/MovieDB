<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Length(min=2, max=150)
     */
    private $title;

     /**
     * @ORM\Column(type="string")
     */
    private $posterFilename;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotNull
     * @Assert\Type(\DateTime::class )
     */
    private $releaseDate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="movies")
     * @Assert\Count(min=1, max=5)
     * 
     */
    private $categories;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Post", mappedBy="movies")
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity=MovieActor::class, mappedBy="movie", orphanRemoval=true)
     */
    private $movieActors;

    /**
     * @ORM\ManyToMany(targetEntity=Person::class, inversedBy="writedMovies")
     * @ORM\JoinTable(name="movie_writer")
     * @Assert\Count(min=1)
     * @Assert\NotNull
     */
    private $writers;

     /**
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="directedMovies")
     * @Assert\Count(min=1)
     * @Assert\NotNull
     */
    private $director;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->movieActors = new ArrayCollection();
        $this->writers = new ArrayCollection();
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

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->addMovie($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            $post->removeMovie($this);
        }

        return $this;
    }

    /**
     * @return Collection|MovieActor[]
     */
    public function getMovieActors(): Collection
    {
        return $this->movieActors;
    }

    public function addMovieActor(MovieActor $movieActor): self
    {
        if (!$this->movieActors->contains($movieActor)) {
            $this->movieActors[] = $movieActor;
            $movieActor->setMovie($this);
        }

        return $this;
    }

    public function removeMovieActor(MovieActor $movieActor): self
    {
        if ($this->movieActors->contains($movieActor)) {
            $this->movieActors->removeElement($movieActor);
            // set the owning side to null (unless already changed)
            if ($movieActor->getMovie() === $this) {
                $movieActor->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getWriters(): Collection
    {
        return $this->writers;
    }

    public function addWriter(Person $writer): self
    {
        if (!$this->writers->contains($writer)) {
            $this->writers[] = $writer;
            $writer->addWritedMovie($this);
        }

        return $this;
    }

    public function removeWriter(Person $writer): self
    {
        if ($this->writers->contains($writer)) {
            $this->writers->removeElement($writer);
            $writer->removeWritedMovie($this);
        }

        return $this;
    }

    public function getDirector(): ?Person
    {
        return $this->director;
    }

    public function setDirector(?Person $director): self
    {
        $this->director = $director;

        return $this;
    }

    public function getPosterFilename(): ?string
    {
        return $this->posterFilename;
    }

    public function setPosterFilename(string $posterFilename): self
    {
        $this->posterFilename = $posterFilename;

        return $this;
    }
}