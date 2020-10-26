<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\Security;

class TaskService
{
    private $entityManager;

    private $rateInfoService;
    
    public function __construct(EntityManagerInterface $manager, RateInfoService $rateInfoService)
    {
        $this->entityManager = $manager;
        $this->rateInfoService = $rateInfoService;
    }
    
    public function all() : array
    {
        return $this->entityManager
        ->getRepository(Task::class)
        ->findAll();
    }
    
    public function oneById(int $id) : Task
    {
        return $this->entityManager
        ->getRepository(Task::class)
        ->find($id);
    }
    
    public function create(Task $task) : Task
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
        
        return $task;
    }
    
    public function allTasksInAllTeamWhereUserParticipate(User $user) : array
    {
        $tasks = [];

        $teams = $user->getTeams();
        
        foreach ($teams as $team) {
            if (!empty($team->getTasks())) {
                foreach ($team->getTasks() as $task) {
                    $tasks[] = $task;
                }
            }
        }
        
        return $tasks;
    }
    
    public function allProjectTaskByUser(User $user) : array
    {
        $tasks = [];
        $projects = $user->getProjects();
        
        foreach ($projects as $project) {
            if (!empty($project->getTasks())) {
                foreach ($project->getTasks() as $task) {
                    $tasks[] = $task;
                }
            }
        }
        
        return $tasks;
    }
    
    public function allProjectTaskExceptUserExecutorByUser(User $user) : array
    {
        $res = [];
        $tasks = $this->allProjectTaskByUser($user);
        
        foreach ($tasks as $task) {
            if ($task->getExecutor()->getId() !== $user->getId()) {
                $res[] = $task;
            }
        }
        
        return $res;
    }


    public function userCreatedTasks(User $user) : Collection
    {
        return $this
            ->entityManager
            ->getRepository(Task::class)
            ->userCreatedTasks($user)
        ;
    }


    public function teamMembersTasksWhereUserParticipated(User $user) : array
    {
        return $this
            ->entityManager
            ->getRepository(Task::class)
            ->teamMembersTasksWhereUserParticipated($user)
        ;
    }


    public function tasksForDashboardByUser(User $user) : ?array
    {
	    return $this->entityManager->getRepository(Task::class)->tasksForDashboardByUser($user);
    }

    public function hasAlreadyMarkedByUserAndTask(User $user, Task $task) : bool
    {
        return $this->rateInfoService->allByUserAndTask($user, $task)->count() > 0;
    }
}

