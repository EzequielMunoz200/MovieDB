<?php

namespace App\Entity;

use App\Repository\MovieActorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=MovieActorRepository::class)
 */
class MovieActor
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(allowNull=false, message="Rôle non renseigné")
     * @Assert\Length(max=255)
     */
    private $characterName;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="movieActors")
     * @ORM\JoinColumn(nullable=false)
     * 
     */
    private $movie;

    /**
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="movieActors")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    private $person;

    public function __toString()
    {
        return $this->characterName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCharacterName(): ?string
    {
        return $this->characterName;
    }

    public function setCharacterName(?string $characterName): self
    {
        $this->characterName = $characterName;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }

 
}
