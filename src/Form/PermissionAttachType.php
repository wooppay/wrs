<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Permission;
use Doctrine\ORM\EntityManagerInterface;

class PermissionAttachType extends AbstractType
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $permissions = $this->entityManager
        ->getRepository(Permission::class)
        ->findAll();
        
        $builder
        ->add('permission_id', ChoiceType::class, [
            'choices' => $permissions,
            'choice_label' => function($permission) {
                return $permission->getName();
            },
            'choice_value' => 'id',
        ])
        ->add('save', SubmitType::class);
    }
}

