<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Permission;
use Doctrine\Common\Collections\Collection;

class PermissionService
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }
    
    public function all() : array
    {
        return $this->entityManager
        ->getRepository(Permission::class)
        ->findAll();
    }
    
    public function create(Permission $permission) : Permission
    {
        $this->entityManager->persist($permission);
        $this->entityManager->flush();
        
        return $permission;
    }
    
    public function byId(int $id) : Permission
    {
        return $this->entityManager
        ->getRepository(Permission::class)
        ->find($id);
    }
    
    public function byName(string $name) : ?Permission
    {
        return $this->entityManager
        ->getRepository(Permission::class)
        ->byName($name);
    }
}

