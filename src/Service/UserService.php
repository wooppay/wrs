<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Enum\UserEnum;

class UserService
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }
    
    public function all() : array
    {
        return $this->entityManager
        ->getRepository(User::class)
        ->findAll();
    }
    
    public function byId(int $id) : User
    {
        return $this->entityManager
        ->getRepository(User::class)
        ->find($id);
    }
    
    public function approve(User $user) : void
    {
        $user->setStatus(UserEnum::APPROVED);
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
    
    public function deactivate(User $user) : void
    {
        $user->setStatus(UserEnum::NOT_APPROVED);
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}

