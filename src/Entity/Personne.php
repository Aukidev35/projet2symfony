<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use App\Trait\TimeStampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Personne
{
    use TimeStampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 4,
        max: 50,
        minMessage: 'Le mot doit faire au minimum 4 caractère',
        maxMessage: 'le mot ne doit pas dépasser 50 caractère'
    )]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $age = null;

    #[ORM\OneToOne(inversedBy: 'personne', cascade: ['persist', 'remove'])]
    private ?Profil $profil = null;

    #[ORM\ManyToMany(targetEntity: Hobby::class)]
    private Collection $hobbies;

    #[ORM\ManyToOne(inversedBy: 'personnes')]
    private ?Job $job = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'personnes')]
    private ?User $createdBy = null;

    // #[ORM\Column(nullable: true)]
    // private ?\DateTimeImmutable $createdAt = null;

    // #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    // private ?\DateTimeInterface $updateAt = null;

    public function __construct()
    {
        $this->hobbies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }


    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * @return Collection<int, Hobby>
     */
    public function getHobbies(): Collection
    {
        return $this->hobbies;
    }

    public function addHobby(Hobby $hobby): self
    {
        if (!$this->hobbies->contains($hobby)) {
            $this->hobbies->add($hobby);
        }

        return $this;
    }

    public function removeHobby(Hobby $hobby): self
    {
        $this->hobbies->removeElement($hobby);

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }

    // public function getCreatedAt(): ?\DateTimeImmutable
    // {
    //     return $this->createdAt;
    // }

    // public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    // {
    //     $this->createdAt = $createdAt;

    //     return $this;
    // }

    // public function getUpdateAt(): ?\DateTimeInterface
    // {
    //     return $this->updateAt;
    // }

    // public function setUpdateAt(?\DateTimeInterface $updateAt): self
    // {
    //     $this->updateAt = $updateAt;

    //     return $this;
    // }
    
    // #[ORM\PrePersist]
    // public function onPrePersist()
    // {
    //     $this->createdAt = new \DateTimeImmutable();
    //     $this->updateAt = new \DateTime();
    // }

    // #[ORM\PreUpdate]
    // public function onPreUpdate()
    // {
    //     $this->updateAt = new \DateTime();
    // }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}
