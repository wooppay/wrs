<?php

namespace App\Form;

use App\Entity\ProfileInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProfileInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
                    'Male' => 0,
                    'Female' => 1
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
            ->add('country', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Country'
                ]
            ])
            ->add('city', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'City'
                ]
            ])
            ->add('jobPosition', null, [
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
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProfileInfo::class,
        ]);
    }
}
