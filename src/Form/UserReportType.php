<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\RoleEnum;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
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
    	$optionsForChoiceField = [
		    'choice_label' => 'Выберите пользователя',
		    'choice_value' => 'id',
		    'placeholder' => 'Выберите пользователя',
		    'label' => 'Пользователь',
		    'required' => true,
	    ];

    	//TODO: Do lazy load
    	if (isset($options['userService'])) {
		    $optionsForChoiceField['choice_loader'] = new CallbackChoiceLoader(function () use ($options) {
			    return $options['userService']->allByRoleName(RoleEnum::DEVELOPER);
		    });
	    }


        $builder
            ->add('user', ChoiceType::class, $optionsForChoiceField)
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
			'userService' => null
        ]);
    }
}
