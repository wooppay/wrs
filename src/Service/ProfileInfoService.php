<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ProfileInfo;

class ProfileInfoService
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }
    
    public function flush(ProfileInfo $profileInfo) : ProfileInfo
    {
        $this->entityManager->persist($profileInfo);
        $this->entityManager->flush();
        
        return $profileInfo;
    }
}