<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Role;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Service\RoleService;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use App\Entity\User;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleAttachType extends AbstractType
{
    private $entityManager;

    private $roleService;
    
    public function __construct(EntityManagerInterface $manager, RoleService $roleService)
    {
        $this->entityManager = $manager;
        $this->roleService = $roleService;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $roles = $this->entityManager
        ->getRepository(Role::class)
        ->findAll();
        $user = $options['user'];
        
        $builder
        ->add('role_id', ChoiceType::class, [
            'choices' => $roles,
            'choice_label' => function($role) {
                return $role;
            },
            'choice_value' => 'id',
        ])
        ->add('save', SubmitType::class);

        $builder->get('role_id')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($user) {
                $roles = $user->getRoles();
                $roleName = $event->getForm()->getData()->getName();

                $form = $event->getForm()->getParent();

                if (in_array($roleName, $roles)) {
                    $form->addError(new FormError('This user has already exist such role'));
                }
            }
        );
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user' => User::class,
        ]);
    }
}

