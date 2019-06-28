<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Skill;
use App\Enum\SkillEnum;
use App\Enum\PermissionEnum;
use App\Entity\Task;
use Symfony\Component\Security\Core\Security;
use App\Entity\Role;

class SkillService
{
    private $entityManager;

    private $security;

    private $roleService;
    
    public function __construct(EntityManagerInterface $manager, Security $security, RoleService $roleService)
    {
        $this->entityManager = $manager;
        $this->security = $security;
        $this->roleService = $roleService;
    }

    public function byId(int $id) : Skill
    {
        return $this
            ->entityManager
            ->getRepository(Skill::class)
            ->byId($id)
        ;
    }
    
    public function all() : array
    {
        return $this->entityManager
        ->getRepository(Skill::class)
        ->findAll();
    }
    
    public function oneSoftById(int $id) : Skill
    {
        return $this->entityManager
        ->getRepository(Skill::class)
        ->findOneBy([
            'type' => SkillEnum::TYPE_SOFT,
            'id' => $id,
        ]);
    }
    
    public function oneTechnicalById(int $id) : Skill
    {
        return $this->entityManager
        ->getRepository(Skill::class)
        ->findOneBy([
            'type' => SkillEnum::TYPE_TECHNICAL,
            'id' => $id,
        ]);
    }
    
    public function allSoft() : array
    {
        return $this->entityManager
        ->getRepository(Skill::class)
        ->findBy([
            'type' => SkillEnum::TYPE_SOFT
        ]);
    }
    
    public function allSoftNotDeleted() : array
    {
        return $this->entityManager
        ->getRepository(Skill::class)
        ->findBy([
            'type' => SkillEnum::TYPE_SOFT,
            'status' => SkillEnum::STATUS_ACTIVE,
        ]);
    }
    
    public function allTechnical() : array
    {
        return $this->entityManager
        ->getRepository(Skill::class)
        ->findBy([
            'type' => SkillEnum::TYPE_TECHNICAL
        ]);
    }
    
    public function allTechnicalNotDeleted() : array
    {
        return $this->entityManager
        ->getRepository(Skill::class)
        ->findBy([
            'type' => SkillEnum::TYPE_TECHNICAL,
            'status' => SkillEnum::STATUS_ACTIVE,
        ]);
    }
    
    public function createSoft(Skill $skill) : Skill
    {
        $this->entityManager->persist($skill);
        
        $skill->setType(SkillEnum::TYPE_SOFT);
        
        $this->entityManager->flush();
        
        return $skill;
    }

    public function updateSoft(Skill $skill) : Skill
    {
        $this->entityManager->persist($skill);
        $this->entityManager->flush();
        
        return $skill;
    }
    
    public function createTechnical(Skill $skill) : Skill
    {
        $this->entityManager->persist($skill);
        
        $skill->setType(SkillEnum::TYPE_TECHNICAL);
        
        $this->entityManager->flush();
        
        return $skill;
    }
    
    public function deleteSkill(Skill $skill) : bool
    {
        $skill->setStatus(SkillEnum::STATUS_DELETED);
        
        $this->entityManager->persist($skill);
        $this->entityManager->flush();
        
        return true;
    }
    
    public function softByRole(Role $role) : ?array
    {
        return $this
            ->entityManager
            ->getRepository(Skill::class)
            ->softByRole($role)
        ;
    }
    
    public function allByRole(Role $role) : ?array
    {
        return $this
            ->entityManager
            ->getRepository(Skill::class)
            ->allByRole($role)
        ;
    }


    public function technicalByRole(Role $role) : ?array
    {
        return $this
            ->entityManager
            ->getRepository(Skill::class)
            ->technicalByRole($role)
        ;
    }


    public function skillsAuthorProductOwnerByTask(Task $task) : array
    {
        $skill = [];
        $executor = $task->getExecutor();

        if ($this->security->isGranted(PermissionEnum::CAN_BE_TEAMLEAD, $executor)) {
            $skills = $this->allByRole($this->roleService->getRoleTeamLead());
        }

        if ($this->security->isGranted(PermissionEnum::CAN_BE_DEVELOPER, $executor) && empty($skills)) {
            $skills = array_merge(
                $this->allByRole($this->roleService->getRoleDeveloper()),
                $this->allByRole($this->roleService->getRoleTeamLead()),
                $this->allByRole($this->roleService->getRoleCustomer())
            );
        }

        return $skills;
    }

    public function skillsAuthorCustomerByTask(Task $task) : array
    {
        $skills = [];
        $executor = $task->getExecutor();
        
        if ($this->security->isGranted(PermissionEnum::CAN_BE_TEAMLEAD, $executor)) {
            $skills = array_merge(
                $this->softByRole($this->roleService->getRoleTeamLead()),
                $this->softByRole($this->roleService->getRoleProductOwner())
            );
        }

        if ($this->security->isGranted(PermissionEnum::CAN_BE_DEVELOPER, $executor) && empty($skills)) {
            $skills = array_merge(
                $this->softByRole($this->roleService->getRoleDeveloper()),
                $this->softByRole($this->roleService->getRoleTeamLead()),
                $this->softByRole($this->roleService->getRoleProductOwner())
            );
        }

        return $skills;
    }

    public function skillsAuthorTeamLeadByTask(Task $task) : array
    {
        $skills = [];
        $executor = $task->getExecutor();

        if ($this->security->isGranted(PermissionEnum::CAN_BE_DEVELOPER, $executor)) {
            $skills = array_merge(
                $this->allByRole($this->roleService->getRoleDeveloper()),
                $this->softByRole($this->roleService->getRoleCustomer()),
                $this->softByRole($this->roleService->getRoleProductOwner())
            );
        }

        return $skills;
    }

    public function skillsAuthorDeveloperByTask(Task $task) : array
    {
        return array_merge(
            $this->softByRole($this->roleService->getRoleCustomer()),
            $this->softByRole($this->roleService->getRoleProductOwner()),
            $this->allByRole($this->roleService->getRoleTeamLead())
        );
    }
}

