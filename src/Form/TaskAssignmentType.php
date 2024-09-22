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
            ->add('task_id', EntityType::class, [
                'class' => Task::class,
                'choice_label' => 'title',
                'placeholder' => 'Select a task',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',                    
                ],
            ])
            ->add('user_id', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
                'placeholder' => 'Select a User',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',                    
                ],
            ])
            // ->add('user_id', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'name',
            //     'multiple' => true, // Enables multi-select
            //     'expanded' => true, // Set to true for checkboxes
            //     'placeholder' => 'Select users',
            //     'required' => true,
            //     'attr' => [
            //         'class' => 'form-control',                    
            //     ],
            // ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TaskAssignment::class,
        ]);
    }
}
