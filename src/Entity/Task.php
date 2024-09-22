<?php

namespace App\Entity;

use App\Components\TaskStatusEnum;
use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Task Name cannot be blank.')]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(enumType: TaskStatusEnum::class)]
    private ?TaskStatusEnum $status = TaskStatusEnum::PENDING;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    /**
     * @var Collection<int, TaskAssignment>
     */
    #[ORM\OneToMany(targetEntity: TaskAssignment::class, mappedBy: 'task_id')]
    private Collection $taskAssignments;

    public function __construct()
    {
        $this->taskAssignments = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?TaskStatusEnum
    {
        return $this->status;
    }

    public function setStatus(TaskStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

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
            $taskAssignment->setTaskId($this);
        }

        return $this;
    }

    public function removeTaskAssignment(TaskAssignment $taskAssignment): static
    {
        if ($this->taskAssignments->removeElement($taskAssignment)) {
            // set the owning side to null (unless already changed)
            if ($taskAssignment->getTaskId() === $this) {
                $taskAssignment->setTaskId(null);
            }
        }

        return $this;
    }
}
