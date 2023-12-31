<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: TypeRepository::class)]
#[ApiResource(
    order: ['name' => 'ASC'],
    normalizationContext: ['groups' => ['getMovie']],
	operations: [
        new GetCollection(
            paginationEnabled: false
        ) ,  
		new Get()
    ]
	
	)]
	#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'name' =>'partial'])]
	
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("getMovie")]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: MovieHasType::class)]
	#[Groups("getMovie")]
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
