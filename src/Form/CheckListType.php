<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Skill;
use App\Service\PermissionService;
use App\Service\UserService;
use App\Enum\PermissionEnum;

class CheckListType extends AbstractType
{
    private $permissionService;

    private $userService;

    public function __construct(PermissionService $permissionService, UserService $userService)
    {
        $this->permissionService = $permissionService;
        $this->userService = $userService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $task = $options['task'];
        $questionWithChoiceIterator = 1;

        foreach ($options['skills'] as $skill) {
            $inputName = 'option_' . $skill->getId();

            if ($skill->getShowNote() == true) {
                $builder
                    ->add(
                        $builder->create('question_with_note_' . $questionWithChoiceIterator, FormType::class, [
                            'by_reference' => true,
                            'attr' => [
                                'class' => 'choice_with_note'
                            ]
                        ])
                        ->add($inputName . '_value', ChoiceType::class, [
                            'choices' => [
                                'Yes' => 1,
                                'No' => 0,
                            ],
                            'label' => $skill->getContent(),
                            'expanded' => true,
                            'multiple' => false,
                        ])
                        ->add($inputName . '_note', TextareaType::class, [
                            'label' => 'Note',
                            'required' => false
                        ])
                    )
                    ->add($inputName . '_skill', HiddenType::class, [
                        'data' => $skill->getId()
                    ])
                ;

                $questionWithChoiceIterator++;
            } else {
                $builder
                    ->add($inputName . '_value', ChoiceType::class, [
                        'choices' => [
                            'Yes' => 1,
                            'No' => 0,
                        ],
                        'label' => $skill->getContent(),
                        'expanded' => true,
                        'multiple' => false,
                    ])
                    ->add($inputName . '_skill', HiddenType::class, [
                        'data' => $skill->getId()
                    ])
                ;
            }

            $permissions = $skill->getRole()->getPermissions();

            $pDeveloper = $this->permissionService->byName(PermissionEnum::CAN_BE_DEVELOPER);
            $pTeamLead = $this->permissionService->byName(PermissionEnum::CAN_BE_TEAMLEAD);
            $pCustomer = $this->permissionService->byName(PermissionEnum::CAN_BE_CUSTOMER);
            $pOwner = $this->permissionService->byName(PermissionEnum::CAN_BE_PRODUCT_OWNER);
            
            // todo guess role and get task user according to role
            $guessRole = null;
            $user = null;

            if ($permissions->contains($pDeveloper)) {
                $user = $task->getExecutor();
            } elseif ($permissions->contains($pTeamLead)) {
                $user = $this->userService->teamLeadByTask($task);
            } elseif ($permissions->contains($pCustomer)) {
                $user = $task->getProject()->getCustomer();
            } elseif ($permissions->contains($pOwner)) {
                $user = $task->getProject()->getOwner();
            }

            $builder->add($inputName . '_user', HiddenType::class, [
                'data' => $user->getId()
            ]);

        }

        $builder->add('save', SubmitType::class);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'skills' => null,
            'task' => null,
        ]);
    }
}
