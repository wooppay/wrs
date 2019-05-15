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
        
        if ($security->isGranted(PermissionEnum::CAN_SEE_TASKS_ASSIGNED_TO_ME, $user)) {
            $tasks = $user->getTasks();
        }
        
        if ($security->isGranted(PermissionEnum::CAN_SEE_MY_TEAM_TASKS, $user)) {
            $tasks = $taskService->allTasksInAllTeamWhereUserParticipate($user);
        }
        
        if ($security->isGranted(PermissionEnum::CAN_SEE_ALL_TASKS, $user)) {
            $tasks = $taskService->all();
        }
        
        return $this->render('dashboard/main.html.twig', [
            'tasks' => $tasks,
        ]);
    }
}

