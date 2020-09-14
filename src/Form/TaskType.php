<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\Team;
use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use App\Service\TeamService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TaskType extends AbstractType
{
    private $teamService;

    private $router;
    
    public function __construct(TeamService $teamService, UrlGeneratorInterface $router)
    {
        $this->teamService = $teamService;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['formAction'] == 'create') {
            $formAction = $this->router->generate('app_dashboard_task_create');
            $projectDisabled = false;
        }

        if ($options['formAction'] == 'update') {
            $formAction = $this->router->generate('app_dashboard_task_update', [
                'id' => $builder->getData()->getId()
            ]);
            $projectDisabled = true;
        }

        $builder
            ->setAction($formAction)
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
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'placeholder' => 'Choose project',
                'choice_label' => 'name',
                'disabled' => $projectDisabled
            ])
            ->add('save', SubmitType::class)
        ;

        if ($options['formAction'] == 'create') {
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    $form = $event->getForm();
    
                    $task = $event->getData();
                    $project = $task->getProject();
                    $team = null === $project ? [] : $project->getTeam();

                    $form->add('team', EntityType::class, [
                        'class' => Team::class,
                        'placeholder' => 'Project team',
                        'choices' => $team,
                        'attr' => [
                            'readonly' => true,
                        ],
                    ]);
                }
            );
        }

        if ($options['formAction'] == 'update') {
            $builder->add('team', EntityType::class, [
                'class' => Team::class,
                'choices' => [$builder->getData()->getProject()->getTeam()],
                'attr' => [
                    'readonly' => true,
                ],
                'disabled' => true
            ]);
        }

        $builder->get('project')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $project = $event->getForm()->getData();

                $form = $event->getForm()->getParent();
                
                $team = $project->getTeam();

                $form->add('team', EntityType::class, [
                    'class' => Team::class,
                    'placeholder' => 'Project team',
                    'choices' => [$team],
                ]);

            }
        );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'users' => [],
            'formAction' => 'create'
        ]);

        $resolver->setAllowedTypes('formAction', 'string');
    }
}
