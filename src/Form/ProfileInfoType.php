<?php

namespace App\Form;

use App\Entity\ProfileInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProfileInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $width = '150';
        $builder
            ->add('firstname', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Firstname',
                    'width' => $width
                ]
            ])
            ->add('surname', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Surname',
                    'width' => $width
                ]
            ])
            ->add('gender', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Gender',
                    'width' => $width
                ]
            ])
            ->add('age', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Age',
                    'width' => $width
                ]
            ])
            ->add('country', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Country',
                    'width' => $width
                ]
            ])
            ->add('city', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'City',
                    'width' => $width
                ]
            ])
            ->add('jobPosition', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Job Position',
                    'width' => $width
                ]
            ])
            ->add('githubLink', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Github Link',
                    'width' => $width
                ]
            ])
            ->add('gitlabLink', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Gitlab Link',
                    'width' => $width
                ]
            ])
            ->add('telegramLink', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Telegram Link',
                    'width' => $width
                ]
            ])
            ->add('skypeLink', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Skype Link',
                    'width' => $width
                ]
            ])
            ->add('personalLink', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Personal Link',
                    'width' => $width
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
