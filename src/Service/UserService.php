<?php
namespace App\Service;

use App\Component\GeneratePDFComponent;
use App\Entity\RateInfo;
use App\Entity\Team;
use App\Enum\PermissionEnum;
use App\Enum\RateInfoEnum;
use App\Enum\SkillEnum;
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

    public function __construct(EntityManagerInterface $manager = null, RoleService $roleService = null, Security $security = null)
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

    public function allExceptAdmin()
    {
        return $this->entityManager
        ->getRepository(User::class)
        ->allExceptAdmin();
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

    public function allByRole(array $roles) : ?array
    {
	    return $this->entityManager->getRepository(User::class)->findByRoleName($roles);
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
    public function allForSelectByEntities(array $usersEntities) : ?array
    {
	    $usersForSelect = [];
		array_map(function($value) use (&$usersForSelect) {
			/** @var User $value **/
			$usersForSelect[] = ['id' => $value->getId(), 'email' => $value->getEmail()];
		}, $usersEntities);

		return $usersForSelect;
    }

	public function byUserAndTime(User $user, string $dateFrom, string $dateTo) : ?array
	{
		return $this->entityManager->getRepository(Task::class)->byUserAndTime($user, $dateFrom, $dateTo);
	}

	public function countRatesByParams(User $user, int $value, string $type, array $tasksIds) : int
	{
		return count($this
			->entityManager
			->getRepository(RateInfo::class)
			->allRatesByParams($user, $value, $type, $tasksIds));
	}

    public function makeReportData(User $user, string $dateFrom, string $dateTo) : array
    {
		$tasks = $this->byUserAndTime($user, $dateFrom, $dateTo);
		$tasksArray = [];
		$tasksIds = [];

	    /** @var Task $task */
		foreach ($tasks as $task) {
			$tasksArray[] = $task->toArrayForReport($user);
			$tasksIds[] = $task->getId();
		}

		$rates = [
			'positiveSoft' => $this->countRatesByParams($user, RateInfoEnum::POSITIVE, SkillEnum::TYPE_SOFT, $tasksIds),
			'negativeSoft' => $this->countRatesByParams($user, RateInfoEnum::NEGATIVE, SkillEnum::TYPE_SOFT, $tasksIds),
			'positiveHard' => $this->countRatesByParams($user, RateInfoEnum::POSITIVE, SkillEnum::TYPE_TECHNICAL, $tasksIds),
			'negativeHard' => $this->countRatesByParams($user, RateInfoEnum::NEGATIVE, SkillEnum::TYPE_TECHNICAL, $tasksIds)
		];


		return ['tasks' => $tasksArray, 'rates' => $rates, 'countRates' => array_sum($rates)];
    }
}

