<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Skill;
use App\Enum\SkillEnum;
use App\Enum\PermissionEnum;
use App\Entity\Task;
use Symfony\Component\Security\Core\Security;

class SkillService
{
    private $entityManager;

    private $security;
    
    public function __construct(EntityManagerInterface $manager, Security $security)
    {
        $this->entityManager = $manager;
        $this->security = $security;
    }

    public function byId(int $id) : Skill
    {
        return $this
            ->entityManager
            ->getRepository(Skill::class)
            ->byId($id)
        ;
    }
    
    public function executorSkillByTask(Task $task) : array
    {
        return $this->entityManager
        ->getRepository(Skill::class)
        ->executorSkillByTask($task);
    }

    public function executorSoftSkillByTask(Task $task) : array
    {
        return $this->entityManager
        ->getRepository(Skill::class)
        ->executorSoftSkillByTask($task);
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
    
    public function customerSkillByTask(Task $task) : array
    {
        return $this->entityManager
        ->getRepository(Skill::class)
        ->customerSkillByTask($task);
    }

    public function customerSoftSkillByTask(Task $task) : array
    {
        return $this->entityManager
        ->getRepository(Skill::class)
        ->customerSoftSkillByTask($task);
    }


    public function leadSkillByTask(Task $task) : array
    {
        return $this->entityManager
        ->getRepository(Skill::class)
        ->leadSkillByTask($task);
    }

    public function leadSoftSkillByTask(Task $task) : array
    {
        return $this->entityManager
        ->getRepository(Skill::class)
        ->leadSoftSkillByTask($task);
    }

    public function ownerSoftSkillByTask(Task $task) : array
    {
        return $this
            ->entityManager
            ->getRepository(Skill::class)
            ->ownerSoftSkillByTask($task)
        ;
    }

    
    public function softByRole(Role $role) : ?Collection
    {
        return $this
            ->entityManager
            ->getRepository(Skill::class)
            ->softByRole($role)
        ;
    }


    public function devSkillByTask(Task $task) : array
    {
        return $this
            ->entityManager
            ->getRepository(Skill::class)
            ->devSkillByTask($task)
        ;
    }

    public function skillsAuthorProductOwnerByTask(Task $task) : array
    {
        $skill = [];
        $executor = $task->getExecutor();

        if ($this->security->isGranted(PermissionEnum::CAN_BE_CUSTOMER, $executor)) {
            $skills = $this->executorSkillByTask($task);
        }

        if ($this->security->isGranted(PermissionEnum::CAN_BE_TEAMLEAD, $executor)) {
            $skills = $this->executorSkillByTask($task);
        }

        if ($this->security->isGranted(PermissionEnum::CAN_BE_DEVELOPER, $executor)) {
            $skills = array_merge(
                $this->executorSkillByTask($task),
                $this->leadSkillByTask($task),
                $this->customerSkillByTask($task)
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
                $this->executorSoftSkillByTask($task),
                $this->ownerSoftSkillByTask($task)
            );
        }

        if ($this->security->isGranted(PermissionEnum::CAN_BE_DEVELOPER, $executor)) {
            $skills = array_merge(
                $this->executorSoftSkillByTask($task),
                $this->leadSoftSkillByTask($task),
                $this->ownerSoftSkillByTask($task)
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
                $this->devSkillByTask($task),
                $this->customerSoftSkillByTask($task),
                $this->ownerSoftSkillByTask($task)
            );
        }

        return $skills;
    }

    public function skillsAuthorDeveloperByTask(Task $task) : array
    {
        return array_merge(
            $this->leadSkillByTask($task),
            $this->customerSoftSkillByTask($task),
            $this->ownerSoftSkillByTask($task)
        );
    }
}

