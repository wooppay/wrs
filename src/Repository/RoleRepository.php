<?php

namespace App\Repository;

use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Service\PermissionService;
use App\Enum\PermissionEnum;
use App\Entity\Permission;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Role|null find($id, $lockMode = null, $lockVersion = null)
 * @method Role|null findOneBy(array $criteria, array $orderBy = null)
 * @method Role[]    findAll()
 * @method Role[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoleRepository extends ServiceEntityRepository
{
    private $permissionService;

    public function __construct(ManagerRegistry $registry, PermissionService $permissionService)
    {
        parent::__construct($registry, Role::class);

        $this->permissionService = $permissionService;
    }

    protected function recognizeRoleByPermission(Permission $permission) : ?Role
    {
        $roles = $this->findAll();
        $res = null;

        foreach ($roles as $role) {
            $permissions = $role->getPermissions();

            if ($permissions->contains($permission)) {
                $res = $role;
                break;
            }
        }

        return $res;

    }

    public function getRoleAdmin() : ?Role
    {
        $permission = $this->permissionService->byName(PermissionEnum::CAN_BE_ADMIN);

        return $this->recognizeRoleByPermission($permission);
    }

    public function getRoleProductOwner() : ?Role
    {
        $permission = $this->permissionService->byName(PermissionEnum::CAN_BE_PRODUCT_OWNER);

        return $this->recognizeRoleByPermission($permission);
    }

    public function getRoleCustomer() : ?Role
    {
        $permission = $this->permissionService->byName(PermissionEnum::CAN_BE_CUSTOMER);

        return $this->recognizeRoleByPermission($permission);
    }

    public function getRoleTeamLead() : ?Role
    {
        $permission = $this->permissionService->byName(PermissionEnum::CAN_BE_TEAMLEAD);

        return $this->recognizeRoleByPermission($permission);
    }

    public function getRoleDeveloper() : ?Role
    {
        $permission = $this->permissionService->byName(PermissionEnum::CAN_BE_DEVELOPER);

        return $this->recognizeRoleByPermission($permission);
    }





    public function findOneByName(string $name): ?Role
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
