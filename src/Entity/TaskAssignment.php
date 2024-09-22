<?php

namespace App\Entity;

use App\Repository\TaskAssignmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskAssignmentRepository::class)]
#[UniqueEntity(
    fields: ['task_id', 'user_id'], 
    message: 'This task is already assigned to this user.'
)]
class TaskAssignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'taskAssignments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Task cannot be blank.')]
    private ?Task $task_id = null;

    #[ORM\ManyToOne(inversedBy: 'taskAssignments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'User cannot be blank.')]
    private ?User $user_id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaskId(): ?Task
    {
        return $this->task_id;
    }

    public function setTaskId(?Task $task_id): static
    {
        $this->task_id = $task_id;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

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
}
