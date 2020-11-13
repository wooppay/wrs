<?php

namespace App\Form;

use App\Entity\JobPosition;
use App\Entity\Country;
use App\Entity\City;
use App\Entity\ProfileInfo;
use App\Enum\GenderEnum;
use App\Service\JobPositionService;
use App\Service\CountryService;
use App\Service\CityService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProfileInfoType extends AbstractType
{
    private $jobPositionService;

    private $countryService;

    private $cityService;

    public function __construct(JobPositionService $jobPositionService, CountryService $countryService, CityService $cityService)
    {
        $this->jobPositionService = $jobPositionService;
        $this->countryService = $countryService;
        $this->cityService = $cityService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $jobPositions = $this->jobPositionService->getAllActiveJobPositions();
        array_unshift($jobPositions, null);
        $countries = $this->countryService->allActive();
        array_unshift($countries, null);
        $cities = $this->cityService->allActive();
        array_unshift($cities, null);

        $builder
            ->add('firstname', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Firstname'
                ]
            ])
            ->add('surname', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Surname'
                ]
            ])
            ->add('gender', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Gender' => null,
                    'Male' => GenderEnum::MALE,
                    'Female' => GenderEnum::FEMALE
                ],
                'choice_attr' => function($choice, $key, $value) {
                    if ($choice === null) {
                        return [
                            'disabled' => true
                        ];
                    } else {
                        return [
                            'disabled' => false
                        ];
                    }
                },
                'attr' => [
                    'placeholder' => 'Gender'
                ]
            ])
            ->add('age', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Age'
                ]
            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choices' => $countries,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Country'
                ]
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choices' => $cities,
                'label' => false,
                'attr' => [
                    'placeholder' => 'City'
                ]
            ])
            ->add('jobPosition', EntityType::class, [
                'class' => JobPosition::class,
                'choices' => $jobPositions,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Job Position'
                ]
            ])
            ->add('githubLink', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Github Link'
                ]
            ])
            ->add('gitlabLink', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Gitlab Link'
                ]
            ])
            ->add('telegramLink', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Telegram Link'
                ]
            ])
            ->add('skypeLink', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Skype Link'
                ]
            ])
            ->add('personalLink', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Personal Link'
                ]
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProfileInfo::class,
        ]);
    }
}
