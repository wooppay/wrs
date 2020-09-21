<?php
namespace App\Service;

use App\Entity\Team;
use App\Enum\PermissionEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Enum\UserEnum;
use App\Entity\Task;
use Symfony\Component\Security\Core\Security;

class UserService
{
    private $entityManager;
    
    private $role;
    
    public function __construct(EntityManagerInterface $manager, RoleService $roleService, Security $security)
    {
        $this->entityManager = $manager;
        $this->role = $roleService;
        $this->security = $security;
    }
    
    public function all() : array
    {
        return $this->entityManager
        ->getRepository(User::class)
        ->findAll();
    }
    
    public function byId(int $id) : User
    {
        return $this->entityManager
        ->getRepository(User::class)
        ->find($id);
    }
    
    public function approve(User $user) : void
    {
        $user->setStatus(UserEnum::APPROVED);
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
    
    public function deactivate(User $user) : void
    {
        $user->setStatus(UserEnum::NOT_APPROVED);
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
    
    public function allByRoleName(string $name) : array
    {
        $role = $this->role->byName($name);
        return $role->getUsers()->toArray();
    }
    
    public function allExceptAdminAndOwner()
    {
        return $this->entityManager
        ->getRepository(User::class)
        ->allExceptAdminAndOwner();
    }

    public function allApprovedExceptAdminAndOwnerAndCustomer()
    {
        return $this->entityManager
        ->getRepository(User::class)
        ->allApprovedExceptAdminAndOwnerAndCustomer();
    }

    public function allApprovedExceptAdminAndOwnerAndCustomerAndTeamMembers(Team $team) : Collection
    {
        $collection = $this->entityManager->getRepository(User::class)->findAllApprovedExceptList($team->getMembers());

        $result = new ArrayCollection();
        foreach ($collection as $item) {
            if (
                !$this->security->isGranted(PermissionEnum::CAN_BE_PRODUCT_OWNER, $item) &&
                !$this->security->isGranted(PermissionEnum::CAN_BE_ADMIN, $item) &&
                !$this->security->isGranted(PermissionEnum::CAN_BE_CUSTOMER, $item)
            ) {
                $result->add($item);
            }
        }

        return $result;
    }

    public function byEmail(string $email) : User
    {
        return $this
            ->entityManager
            ->getRepository(User::class)
            ->byEmail($email)
        ;
    }


    public function teamLeadByTask(Task $task) : ?User
    {
        return $this
            ->entityManager
            ->getRepository(User::class)
            ->teamLeadByTask($task)
        ;
    }

    //TODO: сделать через чистый SQL запрос ради производительности
    public function allForSelectByRole(string $role) : array
    {
	    $usersForSelect = [];
    	$usersEntities = $this->allByRoleName($role);
		array_map(function($value) use (&$usersForSelect) {
			/** @var User $value **/
			$usersForSelect[$value->getId()] = $value->getEmail();
		}, $usersEntities);

		return $usersForSelect;
    }
}

