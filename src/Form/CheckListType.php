<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['skills'] as $skill) {
            $builder->add('option_' . $skill->getId(), ChoiceType::class, [
                'choices' => [
                    'Yes' => 1,
                    'No' => 0,
                ],
                'label' => $skill->getContent(),
                'expanded' => true,
                'multiple' => false,
            ]);
        }

        $builder->add('save', SubmitType::class);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'skills' => null,
        ]);
    }
}
