<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Enum\RoleEnum;
use App\Entity\Permission;
use Symfony\Component\Security\Core\Security;
use App\Enum\PermissionMarkEnum;
use App\Enum\PermissionEnum;

class SecurityService
{
    private $queryBuilder;
    
    private $passwordEncoder;
    
    private $entityManager;
    
    private $security;
    
    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder, Security $security)
    {
        $this->queryBuilder = $manager->getConnection()->createQueryBuilder();
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $manager;
        $this->security = $security;
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

    public function detachRoleFromUser(User $user, Role $role) : bool
	{
        return $this->entityManager->getRepository(User::class)->detachRole($user, $role);
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
    
    public function accessMarkProductOwnerByUser(User $user) : bool
    {
        return $this->security->isGranted(PermissionEnum::CAN_BE_PRODUCT_OWNER, $user) &&
        $this->security->isGranted(PermissionMarkEnum::CAN_MARK_CUSTOMER, $user) &&
        $this->security->isGranted(PermissionMarkEnum::CAN_MARK_DEVELOPER, $user) &&
        $this->security->isGranted(PermissionMarkEnum::CAN_MARK_TEAM_LEAD, $user);
        
    }
    
    public function accessMarkTeamLeadByUser(User $user) : bool
    {
        return $this->security->isGranted(PermissionEnum::CAN_BE_TEAMLEAD, $user) &&
        $this->security->isGranted(PermissionMarkEnum::CAN_MARK_PRODUCT_OWNER, $user) &&
        $this->security->isGranted(PermissionMarkEnum::CAN_MARK_DEVELOPER, $user) &&
        $this->security->isGranted(PermissionMarkEnum::CAN_MARK_CUSTOMER, $user);
        
    }

    public function accessMarkCustomerByUser(User $user) : bool
    {
        return $this->security->isGranted(PermissionEnum::CAN_BE_CUSTOMER, $user) &&
        $this->security->isGranted(PermissionMarkEnum::CAN_MARK_PRODUCT_OWNER, $user) &&
        $this->security->isGranted(PermissionMarkEnum::CAN_MARK_DEVELOPER, $user) &&
        $this->security->isGranted(PermissionMarkEnum::CAN_MARK_TEAM_LEAD, $user);
        
    }

    public function accessMarkDeveloperByUser(User $user) : bool
    {
        return $this->security->isGranted(PermissionEnum::CAN_BE_DEVELOPER, $user) &&
        $this->security->isGranted(PermissionMarkEnum::CAN_MARK_PRODUCT_OWNER, $user) &&
        $this->security->isGranted(PermissionMarkEnum::CAN_MARK_CUSTOMER, $user) &&
        $this->security->isGranted(PermissionMarkEnum::CAN_MARK_TEAM_LEAD, $user);
        
    }
}

