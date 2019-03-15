<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Role;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class SecurityService
{
    private $queryBuilder;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->queryBuilder = $manager->getConnection()->createQueryBuilder();
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
}

