<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\JobPosition;
use App\Entity\ProfileInfo;

class JobPositionService
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }
    
    public function all() : array
    {
        return $this->entityManager
        ->getRepository(JobPosition::class)
        ->findAll();
    }
    
    public function oneById(int $id) : ?JobPosition
    {
        return $this->entityManager
        ->getRepository(JobPosition::class)
        ->find($id);
    }
    
    public function save(JobPosition $jobPosition) : JobPosition
    {
        $this->entityManager->persist($jobPosition);
        $this->entityManager->flush();
        
        return $jobPosition;
    }

    public function delete(JobPosition $jobPosition) : JobPosition
    {
        $jobPosition->setDeleted(true);
        
        $this->entityManager->persist($jobPosition);
        $this->entityManager->flush();
        
        return $jobPosition;
    }

    public function getAllActiveJobPositions(): array
    {
        return $this->entityManager
            ->getRepository(JobPosition::class)
            ->findBy(['deleted' => false])
        ;
    }

    public function isPositionUsed(JobPosition $jobPosition): bool
    {
        $profiles = $this->entityManager
            ->getRepository(ProfileInfo::class)
            ->findBy(['jobPosition' => $jobPosition])
        ;

        return !empty($profiles);
    }
}