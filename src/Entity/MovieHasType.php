<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\MovieHasTypeRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MovieHasTypeRepository::class)]
#[ApiResource(
	normalizationContext: ['groups' => ['getHasType']],
	operations:[
		new Get(security: "is_granted('PUBLIC_ACCESS')"),
		new Post(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can add movies.',
            status: 301,
			denormalizationContext: ['groups' => ['writeMovie']],
        ),
		]
	
)]
class MovieHasType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'movieHasTypes')]
    #[ORM\JoinColumn(nullable: false)]
	#[Groups(["getHasType","writeMovie"])]
    private ?Movie $movie = null;

    #[ORM\ManyToOne(inversedBy: 'movieHasTypes')]
    #[ORM\JoinColumn(nullable: false)]
	#[Groups(["getHasType","writeMovie"])]
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
