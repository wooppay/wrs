<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProjectType extends AbstractType
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($this->router->generate('app_dashboard_project_create'))
            ->add('name', TextType::class)
            ->add('team', ChoiceType::class, [
                'choices' => $options['teams'],
                'choice_label' => function($team) {
                    return $team->getName();
                },
                'choice_value' => 'id',
            ])
            ->add('customer', ChoiceType::class, [
                'choices' => $options['customers'],
                'choice_label' => function($customer) {
                    return $customer->getEmail();
                },
                'choice_value' => 'id',
            ])
            ->add('description', TextareaType::class)
            ->add('save', SubmitType::class)
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'teams' => [],
            'customers' => [],
        ]);
    }
}

