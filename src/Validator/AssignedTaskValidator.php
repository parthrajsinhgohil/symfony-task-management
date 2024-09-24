<?php

namespace App\Validator;

use App\Entity\TaskAssignment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AssignedTaskValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate(mixed $value, Constraint $constraint): void
    {        
        /* @var AssignedTask $constraint */

        if (null === $value || '' === $value) {
            return;
        }
        
        // Check if there are any existing violations
        if (count($this->context->getViolations()) > 0) {
            return; // Skip this validation if there are existing violations
        }

        $entity = $this->context->getObject();

        if($entity->getId()) {
            $id = $entity->getId();
            
            $existingEntity = $this->entityManager->getRepository(TaskAssignment::class)
                ->createQueryBuilder('ta')
                ->where('ta.task = :task')            
                ->andWhere('ta.id != :id') // Exclude the current entity by ID
                ->setParameter('task', $value)            
                ->setParameter('id', $id)
                ->getQuery()
                ->getOneOrNullResult();
        } else {
            $existingEntity = $this->entityManager->getRepository(TaskAssignment::class)
                ->findOneBy(['task' => $value]);
        }

        if($existingEntity) {
            $existingId = (string) $existingEntity->getTask()->getTitle();

            // TODO: implement the validation here
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $existingId)
                ->addViolation();
        }
    }
}
