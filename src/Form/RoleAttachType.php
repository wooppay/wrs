<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Role;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RoleAttachType extends AbstractType
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $roles = $this->entityManager
        ->getRepository(Role::class)
        ->findAll();
        
        $builder
        ->add('role_id', ChoiceType::class, [
            'choices' => $roles,
            'choice_label' => function($role) {
                return $role->getName();
            },
            'choice_value' => 'id',
        ])
        ->add('save', SubmitType::class);
    }
}

