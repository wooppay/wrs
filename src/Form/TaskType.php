<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('executor')
            ->add('executor', ChoiceType::class, [
                'choices' => $options['users'],
                'choice_label' => function($user) {
                    return $user->getEmail();
                },
                'choice_value' => 'id',
            ])
            ->add('project', ChoiceType::class, [
                'choices' => $options['projects'],
                'choice_label' => function($project) {
                    return $project->getName();
                },
                'choice_value' => 'id',
                ])
            ->add('team', TextType::class, [
                'disabled' => true,
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'users' => [],
            'teams' => [],
            'projects' => [],
        ]);
    }
}
