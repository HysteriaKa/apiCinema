<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MovieHasTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovieHasTypeRepository::class)]
#[ApiResource]
class MovieHasType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'movieHasTypes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Movie $movie = null;

    #[ORM\ManyToOne(inversedBy: 'movieHasTypes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Type $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): static
    {
        $this->movie = $movie;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): static
    {
        $this->type = $type;

        return $this;
    }
}
