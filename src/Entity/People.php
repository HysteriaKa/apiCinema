<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PeopleRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PeopleRepository::class)]
#[ApiResource(
	normalizationContext: ['groups' => ['getPeople']],
	operations: [
        new Get(
            security: "is_granted('PUBLIC_ACCESS')",
            requirements: ['id' => '\d+'],
			normalizationContext: ['groups' => ['getPeople']]
        ),
        new GetCollection(
            security: "is_granted('PUBLIC_ACCESS')",
		),
		new Patch(
			denormalizationContext: ['groups' => ['writePeople']],
			securityPostDenormalize: "is_granted('ROLE_ADMIN')",
            securityPostDenormalizeMessage: 'Sorry, you are not allowed to do this action.'
		),
        new Post(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can add peoples.',
            status: 301,
			denormalizationContext: ['groups' => ['writePeople']],
        ),
		new Delete(
			security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can add peoples.'
		)
	
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact',  'lastname' => 'word_start'])]
#[ApiFilter(PropertyFilter::class, arguments: ['parameterName' => 'lastname'])]
class People
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
	#[Groups(["getPeople","writePeople"])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
	#[Groups(["getPeople","writePeople"])]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
	#[Groups(["getPeople","writePeople"])]
    private ?\DateTimeInterface $dateOfBirth = null;

    #[ORM\Column(length: 255)]
	#[Groups(["getPeople","writePeople"])]
    private ?string $nationality = null;

    #[ORM\OneToMany(mappedBy: 'people', targetEntity: MovieHasPeople::class)]
	#[Groups(["getPeople"])]
	private Collection $movieHasPeople;

    public function __construct()
    {
        $this->movieHasPeople = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): static
    {
        $this->nationality = $nationality;

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
            $movieHasPerson->setPeople($this);
        }

        return $this;
    }

    public function removeMovieHasPerson(MovieHasPeople $movieHasPerson): static
    {
        if ($this->movieHasPeople->removeElement($movieHasPerson)) {
            // set the owning side to null (unless already changed)
            if ($movieHasPerson->getPeople() === $this) {
                $movieHasPerson->setPeople(null);
            }
        }

        return $this;
    }
}
