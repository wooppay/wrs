<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Skill;
use App\Enum\SkillEnum;

class SkillService
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }
    
    public function all() : array
    {
        return $this->entityManager
        ->getRepository(Skill::class)
        ->findAll();
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
}

