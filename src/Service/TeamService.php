<?php
namespace App\Service;

use App\Entity\Team;
use App\Enum\RoleEnum;
use Doctrine\ORM\EntityManagerInterface;

class TeamService
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }
    
    public function all() : array
    {
        return $this->entityManager
        ->getRepository(Team::class)
        ->findAll();
    }
    
    public function hasLeadearInTeam(Team $team) : bool
    {
        $members = $team->getMembers();
        
        $roles = [];
        
        foreach ($members as $member) {
            $roles = array_merge($roles, $member->getRoles());
        }
        
        array_unique($roles);
        
        return in_array(RoleEnum::TEAM_LEAD, $roles);
    }
    
    public function create(Team $team) : bool
    {
        $this->entityManager->persist($team);
        $this->entityManager->flush();
        
        return true;
    }
}

