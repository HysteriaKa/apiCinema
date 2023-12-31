<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\ApiFilter;
use App\Controller\ApiController;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ApiResource(
    order: ['title' => 'ASC'],
    // normalizationContext: ['groups' => ['movie']],
    operations: [
        new Get(
            security: "is_granted('PUBLIC_ACCESS')",
            requirements: ['id' => '\d+'],
            name: 'picture',
            uriTemplate: '/movies/{id}',
            controller: ApiController::class,
			normalizationContext: ['groups' => ['getMovie']]
        ),
        new GetCollection(
            security: "is_granted('PUBLIC_ACCESS')",
		),
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
    ],
)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("getMovie")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["writeMovie","getMovie"])]
    private ?string $title = null;

    #[ORM\Column]
    #[Groups(["writeMovie","getMovie"])]
    private ?int $duration = null;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: MovieHasPeople::class, orphanRemoval: true,cascade: ['persist'])]
    #[Groups(["getMovie","getMovie"])]
	private Collection $movieHasPeople;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: MovieHasType::class,cascade: ['persist'])]
	#[Groups(["writeMovie","getMovie"])]
    private Collection $movieHasTypes;

    #[ORM\Column(length: 255, nullable: true)]
	#[Groups(["getMovie"])]
    private ?string $pictureUrl = null;

    public function __construct()
    {
        $this->movieHasPeople = new ArrayCollection();
        $this->movieHasTypes = new ArrayCollection();
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

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection<int, MovieHasPeople>
     */
    public function getMovieHasPeople(): Collection
    {
        return $this->movieHasPeople;
    }

    public function addMovieHasPerson(MovieHasPeople $movieHasPerson): static
    {
        if (!$this->movieHasPeople->contains($movieHasPerson)) {
            $this->movieHasPeople->add($movieHasPerson);
            $movieHasPerson->setMovie($this);
        }

        return $this;
    }

    public function removeMovieHasPerson(MovieHasPeople $movieHasPerson): static
    {
        if ($this->movieHasPeople->removeElement($movieHasPerson)) {
            // set the owning side to null (unless already changed)
            if ($movieHasPerson->getMovie() === $this) {
                $movieHasPerson->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MovieHasType>
     */
    public function getMovieHasTypes(): Collection
    {
        return $this->movieHasTypes;
    }

    public function addMovieHasType(MovieHasType $movieHasType): static
    {
        if (!$this->movieHasTypes->contains($movieHasType)) {
            $this->movieHasTypes->add($movieHasType);
            $movieHasType->setMovie($this);
        }

        return $this;
    }

    public function removeMovieHasType(MovieHasType $movieHasType): static
    {
        if ($this->movieHasTypes->removeElement($movieHasType)) {
            // set the owning side to null (unless already changed)
            if ($movieHasType->getMovie() === $this) {
                $movieHasType->setMovie(null);
            }
        }
        return $this;
    }

    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    public function setPictureUrl(?string $pictureUrl): static
    {
        $this->pictureUrl = $pictureUrl;

        return $this;
    }
}
