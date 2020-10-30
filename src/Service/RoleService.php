<?php
namespace App\Service;

use App\Entity\User;
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
    
    /**
     * todo pass collection instead instance and
     * create instance inside method
     * 
     * @return Role
     */
    public function create(Role $role) : Role
    {
        $this->entityManager->persist($role);
        $this->entityManager->flush();
        
        return $role;
    }
    
    public function update(Role $role) : Role
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

    public function allByUser(User $user): ?array
    {
        $ids = array_flip($user->getRoles());

        return $this->entityManager
            ->getRepository(Role::class)
            ->findBy(['id' => $ids]);
    }

    public function allTitlesByUser(User $user): ?array
    {
        $roles = $this->allByUser($user);
        $titles = [];

        foreach ($roles as $role) {
            $titles[] = $role->getTitle();
        }

        return $titles;
    }

    public function byName(string $name) : Role
    {
        return $this->entityManager
        ->getRepository(Role::class)
        ->findOneBy([
            'name' => $name,
        ]);
    }


    public function getRoleAdmin() : ?Role
    {
        return $this
            ->entityManager
            ->getRepository(Role::class)
            ->getRoleAdmin()
        ;
    }

    public function getRoleProductOwner() : ?Role
    {
        return $this
            ->entityManager
            ->getRepository(Role::class)
            ->getRoleProductOwner()
        ;
    }

    public function getRoleCustomer() : ?Role
    {
        return $this
            ->entityManager
            ->getRepository(Role::class)
            ->getRoleCustomer()
        ;
    }

    public function getRoleTeamLead() : ?Role
    {
        return $this
            ->entityManager
            ->getRepository(Role::class)
            ->getRoleTeamLead()
        ;
    }

    public function getRoleDeveloper() : ?Role
    {
        return $this
            ->entityManager
            ->getRepository(Role::class)
            ->getRoleDeveloper()
        ;
    }
}

