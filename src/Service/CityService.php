<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\City;

class CityService
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }
    
    public function allActive() : array
    {
        return $this->entityManager
            ->getRepository(City::class)
            ->findBy(['deleted' => false])
        ;
    }
    
    public function oneById(int $id) : ?City
    {
        return $this->entityManager
            ->getRepository(City::class)
            ->find($id)
        ;
    }
    
    public function save(City $city) : City
    {
        $this->entityManager->persist($city);
        $this->entityManager->flush();
        
        return $city;
    }

    public function delete(City $city) : City
    {
        $city->setDeleted(true);
        
        $this->entityManager->persist($city);
        $this->entityManager->flush();
        
        return $city;
    }
}