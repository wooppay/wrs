<?php

namespace App\Form;

use App\Entity\ProfileInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('surname')
            ->add('gender')
            ->add('age')
            ->add('country')
            ->add('city')
            ->add('githubLink')
            ->add('gitlabLink')
            ->add('telegramLink')
            ->add('skypeLink')
            ->add('personalLink')
            ->add('user')
            ->add('jobPosition')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProfileInfo::class,
        ]);
    }
}
