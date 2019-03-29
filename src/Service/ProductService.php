<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    private $queryBuilder;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->queryBuilder = $manager->getConnection()->createQueryBuilder();
    }
    
    public function addMemberToTeam(User $user, Team $team) : bool
    {
        return $this->queryBuilder->insert('member_team')->values([
            'member_id' => ':member_id',
            'team_id' => ':team_id',
        ])->setParameters([
            ':member_id' => $user->getId(),
            ':team_id' => $team->getId(),
        ])->execute() > 0;
    }
}

