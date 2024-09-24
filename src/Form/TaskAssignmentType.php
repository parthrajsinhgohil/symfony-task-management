<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\TaskAssignment;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskAssignmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('task', EntityType::class, [
                'class' => Task::class,
                'choice_label' => 'title',
                'placeholder' => 'Select a task',
                'required' => false,
                'label' => 'Task',
                'attr' => [
                    'class' => 'form-control mt-2 mb-3',                    
                ],
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
                'placeholder' => 'Select a User',
                'required' => false,
                'label' => 'User',
                'attr' => [
                    'class' => 'form-control mt-2 mb-3',                    
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TaskAssignment::class,
        ]);
    }
}
