<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Enum\RoleEnum;

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
}

