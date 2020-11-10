<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Goal;
use App\Entity\User;

class GoalService
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }
    
    public function byUser(User $user) : array
    {
        return $this->entityManager
            ->getRepository(Goal::class)
            ->findBy(['user' => $user])
        ;
    }
    
    public function oneById(int $id) : ?Goal
    {
        return $this->entityManager
            ->getRepository(Goal::class)
            ->oneById($id)
        ;
    }
    
    public function save(User $user, Goal $goal) : Goal
    {
        $goal->setUser($user);
        
        $this->entityManager->persist($goal);
        $this->entityManager->flush();
        
        return $goal;
    }

    public function byName(string $name) : ?Goal
    {
        return $this->entityManager
            ->getRepository(Goal::class)
            ->byName($name)
        ;
    }
}

