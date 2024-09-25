<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    /**
     * @var Collection<int, TaskAssignment>
     */
    #[ORM\OneToMany(targetEntity: TaskAssignment::class, mappedBy: 'user')]
    private Collection $taskAssignments;

    public function __construct()
    {
        $this->taskAssignments = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        if ($this->created_at === null) {
            $this->created_at = $created_at;
        }
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, TaskAssignment>
     */
    public function getTaskAssignments(): Collection
    {
        return $this->taskAssignments;
    }

    public function addTaskAssignment(TaskAssignment $taskAssignment): static
    {
        if (!$this->taskAssignments->contains($taskAssignment)) {
            $this->taskAssignments->add($taskAssignment);
            $taskAssignment->setUser($this);
        }

        return $this;
    }

    public function removeTaskAssignment(TaskAssignment $taskAssignment): static
    {
        if ($this->taskAssignments->removeElement($taskAssignment)) {
            // set the owning side to null (unless already changed)
            if ($taskAssignment->getUser() === $this) {
                $taskAssignment->setUser(null);
            }
        }

        return $this;
    }
}
