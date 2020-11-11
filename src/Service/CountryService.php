<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Country;

class CountryService
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }
    
    public function allActive() : array
    {
        return $this->entityManager
            ->getRepository(Country::class)
            ->findBy(['deleted' => false])
        ;
    }
    
    public function oneById(int $id) : ?Country
    {
        return $this->entityManager
            ->getRepository(Country::class)
            ->find($id)
        ;
    }
    
    public function save(Country $country) : Country
    {
        $this->entityManager->persist($country);
        $this->entityManager->flush();
        
        return $country;
    }

    public function delete(Country $country) : Country
    {
        $country->setDeleted(true);
        
        $this->entityManager->persist($country);
        $this->entityManager->flush();
        
        return $country;
    }

    public function hasCities(Country $country) : bool
    {
        return !$country->getCities()->isEmpty();
    }
}