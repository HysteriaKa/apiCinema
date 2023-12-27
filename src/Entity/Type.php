<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
#[ApiResource]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: MovieHasType::class, orphanRemoval: true)]
    private Collection $movieHasTypes;

    public function __construct()
    {
        $this->movieHasTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
            $movieHasType->setType($this);
        }

        return $this;
    }

    public function removeMovieHasType(MovieHasType $movieHasType): static
    {
        if ($this->movieHasTypes->removeElement($movieHasType)) {
            // set the owning side to null (unless already changed)
            if ($movieHasType->getType() === $this) {
                $movieHasType->setType(null);
            }
        }

        return $this;
    }
}
