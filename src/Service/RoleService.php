<?php
namespace App\Service;

use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;

class RoleService
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }
    
    public function all() : array
    {
        return $this->entityManager
        ->getRepository(Role::class)
        ->findAll();
    }
    
    public function create(Role $role) : Role
    {
        $this->entityManager->persist($role);
        $this->entityManager->flush();
        
        return $role;
    }
    
    public function byId(int $id) : Role
    {
        return $this->entityManager
        ->getRepository(Role::class)
        ->find($id);
    }
}

