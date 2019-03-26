<?php
namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserVoter extends Voter
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }
    
    protected function supports($attribute, $subject)
    {
        if (!$subject instanceof User) {
            return false;
        }
        
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {        
        $roles = $subject->getRoles();
        
        $permissions = [];
        
        
        foreach ($roles as $role) {
            /** @var App\Entity\Role $entity */
            $entity = $this->entityManager
            ->getRepository(Role::class)
            ->findOneByName($role);
            
            foreach ($entity->getPermissions() as $permission) {
                $permissions[] = $permission->getName();
            }
        }
        
        return in_array($attribute, $permissions);
    }

}

