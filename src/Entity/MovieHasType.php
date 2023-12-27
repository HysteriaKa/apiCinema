<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Repository\MovieHasTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
#[ORM\Entity(repositoryClass: MovieHasTypeRepository::class)]

#[ApiResource(
	normalizationContext: ['groups' => ['getType']],
	operations: [
		new Get(security: "is_granted('PUBLIC_ACCESS')"),
		new GetCollection(
            security: "is_granted('PUBLIC_ACCESS')",
		),
		new Patch(
			denormalizationContext: ['groups' => ['writeType']],
			securityPostDenormalize: "is_granted('ROLE_ADMIN')",
            securityPostDenormalizeMessage: 'Sorry, you are not allowed to do this action.'
		),
		new Post(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can add movies.',
            status: 301,
			denormalizationContext: ['groups' => ['writeType']],
        ),
		new Delete(
			security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can add movies.'
		)
		
	]
	
	)]
class MovieHasType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'movieHasTypes')]
	#[Groups(["getType",'writeType'])]
    private ?Movie $movie = null;

    #[ORM\ManyToOne(inversedBy: 'movieHasTypes')]
	#[Groups(['writeMovie','writeType',"getType"])]
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
