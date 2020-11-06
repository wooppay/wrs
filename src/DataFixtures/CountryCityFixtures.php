<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\City;
use App\Entity\Country;

class CountryCityFixtures extends Fixture
{
    public const COUNTRY_KAZAKHSTAN = 'Kazakhstan';
    
    public const CITY_KARAGANDA = 'Karaganda';

    public const CITY_ALMATY = 'Almaty';

    public const CITY_NUR_SULTAN = 'Nur-Sultan';
    
    public function load(ObjectManager $manager)
    {
        $kazakhstan = new Country();
        $karaganda = new City();
        $almaty = new City();
        $nurSultan = new City();

        $kazakhstan->setName(self::COUNTRY_KAZAKHSTAN);
        $karaganda->setName(self::CITY_KARAGANDA);
        $almaty->setName(self::CITY_ALMATY);
        $nurSultan->setName(self::CITY_NUR_SULTAN);

        $karaganda->setCountry($kazakhstan);
        $almaty->setCountry($kazakhstan);
        $nurSultan->setCountry($kazakhstan);

        $kazakhstan->addCity($karaganda);
        $kazakhstan->addCity($almaty);
        $kazakhstan->addCity($nurSultan);

        $manager->persist($kazakhstan);
        $manager->persist($karaganda);
        $manager->persist($almaty);
        $manager->persist($nurSultan);
        $manager->flush();

        $this->addReference(self::COUNTRY_KAZAKHSTAN, $kazakhstan);
        $this->addReference(self::CITY_KARAGANDA, $karaganda);  
        $this->addReference(self::CITY_ALMATY, $almaty);
        $this->addReference(self::CITY_NUR_SULTAN, $nurSultan);
        
    }
}
