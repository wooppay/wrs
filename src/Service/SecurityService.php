<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Enum\RoleEnum;
use App\Entity\Permission;

class SecurityService
{
    private $queryBuilder;
    
    private $passwordEncoder;
    
    private $entityManager;
    
    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->queryBuilder = $manager->getConnection()->createQueryBuilder();
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $manager;
    }
    
    public function setRoleToUser(User $user, Role $role) : bool
    {
        return $this->queryBuilder->insert('user_role')->values([
            'user_id' => ':user_id',
            'role_id' => ':role_id',
        ])->setParameters([
            ':user_id' => $user->getId(),
            ':role_id' => $role->getId(),
        ])->execute() > 0;
    }
    
    public function setPermissionToRole(Role $role, Permission $permission) : bool
    {
        return $this->queryBuilder->insert('role_permission')->values([
            'role_id' => ':role_id',
            'permission_id' => ':permission_id',
        ])->setParameters([
            ':role_id' => $role->getId(),
            ':permission_id' => $permission->getId(),
        ])->execute() > 0;
    }
    
    public function register(UserInterface $user, string $plainPassword) : bool
    {
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $plainPassword)
        );
        
        $role = $this->entityManager
        ->getRepository(Role::class)
        ->findOneByName(RoleEnum::USER);
        
        $this->entityManager->getConnection()->beginTransaction();
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        
        if (!$this->setRoleToUser($user, $role)) {
            $this->entityManager->getConnection()->rollBack();
            throw new \Exception();
        }
        
        $this->entityManager->getConnection()->commit();
        
        return true;
    }
    
    public function deletePermissionById(int $id) : bool
    {
        $permission = $this->entityManager
        ->getRepository(Permission::class)
        ->find($id);
        
        $this->entityManager->remove($permission);
        $this->entityManager->flush();
        
        return true;
    }
    
    public function deleteRolePermission(Role $role, Permission $permission) : bool
    {
        return $this->queryBuilder
        ->delete('role_permission', 'rp')
        ->where('rp.role_id = :role_id AND rp.permission_id = :permission_id')
        ->setParameters([
            ':role_id' => $role->getId(),
            ':permission_id' => $permission->getId(),
        ])
        ->execute() > 0;
    }
}

