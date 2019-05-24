<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use App\Enum\PermissionEnum;
use App\Service\TaskService;

class DashboardController extends AbstractController
{
    public function main(Security $security, TaskService $taskService)
    {
        $user = $this->getUser();
        
        $tasks = [];
        
        if ($security->isGranted(PermissionEnum::CAN_SEE_TASKS_ASSIGNED_TO_ME, $user)) {
            $tasks = array_merge($tasks, $user->getTasks()->toArray());
        }
        
        if ($security->isGranted(PermissionEnum::CAN_SEE_MY_TEAM_TASKS, $user)) {
            $tasks = array_merge($tasks, $taskService->allTasksInAllTeamWhereUserParticipate($user));
        }
        
        if ($security->isGranted(PermissionEnum::CAN_SEE_ALL_TASKS, $user)) {
            $tasks = array_merge($tasks, $taskService->all());
        }
        
        if ($security->isGranted(PermissionEnum::CAN_SEE_ALL_MY_PROJECT_TASKS_EXCEPT_ME, $user)) {
            $tasks = array_merge($tasks, $taskService->allProjectTaskExceptUserExecutorByUser($user));
        }
        
        $tasks = array_unique($tasks, SORT_REGULAR);
        
        return $this->render('dashboard/main.html.twig', [
            'tasks' => $tasks,
        ]);
    }
}

