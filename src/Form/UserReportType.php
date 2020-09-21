<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', ChoiceType::class, [
            	'choice_label' => 'Выберите пользователя',
	            'choice_value' => 'id',
	            'placeholder' => 'Выберите пользователя',
	            'label' => 'Пользователь',
	            'allow_extra_fields' => true,
	            'required' => true,
	            'mapped' => false,
            ])
	        ->add('dateFrom', TextType::class, [
	        	'mapped' => false,
		        'label' => 'От'
	        ])
	        ->add('dateTo', TextType::class, [
		        'mapped' => false,
		        'label' => 'До'
	        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
	        'data_class' => User::class,
        ]);
    }
}
