<?php

namespace App\Repository;

use App\Entity\Skill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\Task;
use App\Service\RoleService;
use Symfony\Component\Security\Core\Security;
use App\Enum\PermissionEnum;
use Doctrine\Common\Collections\Collection;
use App\Service\PermissionService;
use App\Entity\Role;
use App\Enum\SkillEnum;

/**
 * @method Skill|null find($id, $lockMode = null, $lockVersion = null)
 * @method Skill|null findOneBy(array $criteria, array $orderBy = null)
 * @method Skill[]    findAll()
 * @method Skill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkillRepository extends ServiceEntityRepository
{
    private $roleService;
    
    private $securityService;
    
    private $permissionService;
    
    public function __construct(RegistryInterface $registry, RoleService $roleService, Security $security, PermissionService $permissionService)
    {
        parent::__construct($registry, Skill::class);
        
        $this->roleService = $roleService;
        $this->securityService = $security;
        $this->permissionService = $permissionService;
    }
    
    public function executorSkillByTask(Task $task) : array
    {
        $executor = $task->getExecutor();
        $roles = $executor->getRoles();
        
        return $this->byRoles($roles);
    }

    public function executorSoftSkillByTask(Task $task) : array
    {
        $executor = $task->getExecutor();
        $roles = $executor->getRoles();
        
        return $this->softByRoles($roles);
    }

    
    public function leadSkillByTask(Task $task) : array
    {
        $members = $task->getTeam()->getMembers();
        $res = [];
        $teamLeadPermission = $this->permissionService->byName(PermissionEnum::CAN_BE_TEAMLEAD);
        
        foreach ($members as $member) {
            if (!$this->securityService->isGranted(PermissionEnum::CAN_BE_TEAMLEAD, $member)) {
                continue;
            }
            
            $roles = $member->getRoles();
            
            foreach ($roles as $role) {
                $entity = $this->roleService->byName($role);
                $permissions = $entity->getPermissions();
                
                if ($permissions->contains($teamLeadPermission)) {
                    $res[] = $role;
                    
                    break;
                }
            }
        }
        
        return $this->byRoles($res);
    }

    public function devSkillByTask(Task $task) : array
    {
        $members = $task->getTeam()->getMembers();
        $res = [];
        $devPermission = $this->permissionService->byName(PermissionEnum::CAN_BE_DEVELOPER);
        
        foreach ($members as $member) {
            if (!$this->securityService->isGranted(PermissionEnum::CAN_BE_DEVELOPER, $member)) {
                continue;
            }
            
            $roles = $member->getRoles();
            
            foreach ($roles as $role) {
                $entity = $this->roleService->byName($role);
                $permissions = $entity->getPermissions();
                
                if ($permissions->contains($devPermission)) {
                    $res[] = $role;
                    
                    break;
                }
            }
        }
        
        return $this->byRoles($res);
    }


    public function leadSoftSkillByTask(Task $task) : array
    {
        $members = $task->getTeam()->getMembers();
        $res = [];
        $teamLeadPermission = $this->permissionService->byName(PermissionEnum::CAN_BE_TEAMLEAD);
        
        foreach ($members as $member) {
            if (!$this->securityService->isGranted(PermissionEnum::CAN_BE_TEAMLEAD, $member)) {
                continue;
            }
            
            $roles = $member->getRoles();
            
            foreach ($roles as $role) {
                $entity = $this->roleService->byName($role);
                $permissions = $entity->getPermissions();
                
                if ($permissions->contains($teamLeadPermission)) {
                    $res[] = $role;
                    
                    break;
                }
            }
        }
        
        return $this->softByRoles($res);
    }
    
    public function customerSkillByTask(Task $task) : array
    {
        $customer = $task->getProject()->getCustomer();
        
        return $this->byRoles($customer->getRoles());
    }

    public function customerSoftSkillByTask(Task $task) : array
    {
        $customer = $task->getProject()->getCustomer();
        
        return $this->softByRoles($customer->getRoles());
    }


    public function ownerSkillByTask(Task $task) : array
    {
        $owner = $task->getProject()->getOwner();
        
        return $this->byRoles($owner->getRoles());
    }

    public function ownerSoftSkillByTask(Task $task) : array
    {
        $owner = $task->getProject()->getOwner();
        
        return $this->softByRoles($owner->getRoles());
    }

    
    protected function byRoles(array $roles) : array
    {
        $skills = [];
        
        foreach ($roles as $role) {
            $entity = $this->roleService->byName($role);
            
            if (!$entity->getSkills()->isEmpty()) {
                $skills = array_merge($skills, $entity->getSkills()->toArray());
            }
        }
        
        return $skills;
    }

    protected function softByRoles(array $roles) : array
    {
        $skills = [];
        
        foreach ($roles as $role) {
            $entity = $this->roleService->byName($role);
            
            if ($this->softByRole($entity) !== null) {
                $skills = array_merge($skills, $this->softByRole($entity));
            }
        }
        
        return $skills;
    }



    protected function softByRole(Role $role) : ?array
    {
        return $this->findBy([
            'role' => $role,
            'type' => SkillEnum::TYPE_SOFT,
        ]);
    }

    // /**
    //  * @return Skill[] Returns an array of Skill objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Skill
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
