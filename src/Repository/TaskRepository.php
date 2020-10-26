<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use App\Enum\TaskEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use App\Service\TaskService;
use App\Enum\PermissionEnum;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    private $taskService;
    private $security;

    public function __construct(ManagerRegistry $registry, TaskService $taskService, Security $security)
    {
        parent::__construct($registry, Task::class);

        $this->taskService = $taskService;
        $this->security = $security;
    }

    public function userCreatedTasks(User $user) : Collection
    {
        return $user->getMyTasks();
    }

    public function teamMembersTasksWhereUserParticipated(User $user) : array
    {
        $teams = $user->getTeams();
        $members = [];
        $tasks = [];

        foreach ($teams as $team) {
            $users = $team->getMembers();
            
            foreach ($users as $member) {
                $tasks = array_merge($tasks, $member->getTasks()->toArray());
            }
        }

        return $tasks;
    }

    public function tasksForDashboardByUser(User $user) : ?array
    {
	    $query = $this->createQueryBuilder('t');

	    if ($this->security->isGranted(PermissionEnum::CAN_SEE_MAY_CREATED_TASKS, $user)) {
	        $query->orWhere('t.author = :user_id')->setParameter('user_id', $user->getId());
	    }

	    if ($this->security->isGranted(PermissionEnum::CAN_SEE_TASKS_ASSIGNED_TO_ME, $user)) {
		    $query->orWhere('t.executor = :user_id')->setParameter('user_id', $user->getId());
	    }

	    if ($this->security->isGranted(PermissionEnum::CAN_SEE_ALL_MY_PROJECT_TASKS, $user)) {
	       $query->orWhere('t.project IN (:projects)')->setParameter('projects', $user->getProjects());
	    }

	    if ($this->security->isGranted(PermissionEnum::CAN_SEE_ALL_MEMBERS_TASKS_FROM_TEAMS_WHERE_I_PARTICIPATED, $user)) {
		    $query->orWhere('t.team IN (:teams)')->setParameter('teams', $user->getTeams());
	    }

	    return $query
		    ->leftJoin('t.rates', 'rates')
		    ->andWhere('rates.id IS NULL')
		    ->orderBy('t.id', 'DESC')
		    ->getQuery()
		    ->getResult();
    }

    //TODO: Сделать изменение статуса задачи при выполнении, затем получать только выполненные задачи
	public function byUserAndTime(User $user, string $dateFrom, string $dateTo) : ?array
	{

		return $this->createQueryBuilder('t')
			->where('t.executor = :user')
			->andWhere('t.created_at >= :dateFrom')
			->andWhere('t.created_at <= :dateTo')
			//->andWhere('t.status = :status')
			->setParameter('user', $user)
			->setParameter('dateFrom', $dateFrom)
			->setParameter('dateTo', (new \DateTime($dateTo))->modify('1 day'))
			//->setParameter('status', TaskEnum::DONE)
			->orderBy('t.id', 'ASC')
			->getQuery()
			->getResult();
	}

    // /**
    //  * @return Task[] Returns an array of Task objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
