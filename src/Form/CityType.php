<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Country;
use App\Service\CountryService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CityType extends AbstractType
{
    private $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $countries = $this->countryService->allActive();

        $builder
            ->add('name')
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choices' => $countries
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => City::class,
        ]);
    }
}
