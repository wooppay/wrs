<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Enum\UserEnum;

class UserService
{
    private $entityManager;
    
    private $role;
    
    public function __construct(EntityManagerInterface $manager, RoleService $roleService)
    {
        $this->entityManager = $manager;
        $this->role = $roleService;
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
    
    public function allByRoleName(string $name) : array
    {
        $role = $this->role->byName($name);
        return $role->getUsers()->toArray();
    }
    
    public function allExceptAdminAndOwner()
    {
        return $this->entityManager
        ->getRepository(User::class)
        ->allExceptAdminAndOwner();
    }

    public function allApprovedExceptAdminAndOwnerAndCustomer()
    {
        return $this->entityManager
        ->getRepository(User::class)
        ->allApprovedExceptAdminAndOwnerAndCustomer();
    }

    public function byEmail(string $email) : User
    {
        return $this
            ->entityManager
            ->getRepository(User::class)
            ->byEmail($email)
        ;
    }
}

