<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MovieHasPeopleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Enums\SignificanceEnum;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\ApiProperty;

#[ApiResource(
	normalizationContext: ['groups' => ['getMovieHasPeople']],
	operations: [
		new Get( security: "is_granted('PUBLIC_ACCESS')"),
		new GetCollection( security: "is_granted('PUBLIC_ACCESS')"),
		new Patch(
			denormalizationContext: ['groups' => ['writeMovie']],
			securityPostDenormalize: "is_granted('ROLE_ADMIN')",
            securityPostDenormalizeMessage: 'Sorry, you are not allowed to do this action.'
		),
        new Post(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can add movies.',
            status: 301,
			denormalizationContext: ['groups' => ['writeMovie']],
        ),
		new Delete(
			security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can add movies.'
		)
	]
	)]
#[ORM\Entity(repositoryClass: MovieHasPeopleRepository::class)]
class MovieHasPeople
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
	#[Groups(["getMovieHasPeople"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'movieHasPeople')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Movie $movie = null;

    #[ORM\Column(type: 'string', enumType: SignificanceEnum::class, nullable: true)]
	#[Groups(["getMovieHasPeople",'writeMovie'])]
	#[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'enum' => ['principal', 'secondaire'],
            'example' => 'principal'
        ]
    )]
    private ?SignificanceEnum $significance = null;

    #[ORM\Column(length: 255)]
	#[Groups(["getMovieHasPeople","writeMovie"])]
    private ?string $role = null;

    #[ORM\ManyToOne(inversedBy: 'movieHasPeople',cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
	#[Groups(["writeMovie","getMovieHasPeople"])]
    private ?People $people = null;

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

    public function getSignificance(): ?SignificanceEnum
    {
        return $this->significance;
    }

    public function setSignificance(?SignificanceEnum $significance): self
    {
        $this->significance = $significance;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getPeople(): ?People
    {
        return $this->people;
    }

    public function setPeople(?People $people): static
    {
        $this->people = $people;

        return $this;
    }
}
