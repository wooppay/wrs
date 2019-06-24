<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use App\Enum\PermissionEnum;
use App\Service\TaskService;
use App\Service\RateInfoService;
use App\Service\UserService;
use App\Entity\Task;
use App\Form\TaskType;

class DashboardController extends AbstractController
{
    public function main(Security $security, TaskService $taskService, RateInfoService $rateInfoService, UserService $userService)
    {
        $user = $this->getUser();
        
        $tasks = [];
        
        if ($security->isGranted(PermissionEnum::CAN_SEE_MAY_CREATED_TASKS, $user)) {
            $tasks = array_merge($tasks, $taskService->userCreatedTasks($user)->toArray());
        }

        if ($security->isGranted(PermissionEnum::CAN_SEE_ALL_MY_PROJECT_TASKS, $user)) {
            $tasks = array_merge($tasks, $taskService->allProjectTaskByUser($user));
        }

        if ($security->isGranted(PermissionEnum::CAN_SEE_TASKS_ASSIGNED_TO_ME, $user)) {
            $tasks = array_merge($tasks, $user->getTasks()->toArray());
        }

        if ($security->isGranted(PermissionEnum::CAN_SEE_ALL_MEMBERS_TASKS_FROM_TEAMS_WHERE_I_PARTICIPATED, $user)) {
            $tasks = array_merge($tasks, $taskService->teamMembersTasksWhereUserParticipated($user));
        }
        
        $tasks = array_unique($tasks, SORT_REGULAR);
        $receiveMarks = count($user->getRates());
        $authorMarks = count($user->getAuthorRates());


        $tasksUsers = $userService->allApprovedExceptAdminAndOwnerAndCustomer();
        
        $taskForm = $this->createForm(TaskType::class, (new Task()), [
            'users' => $tasksUsers,
        ]);

        return $this->render('dashboard/main.html.twig', [
            'tasks' => $tasks,
            'receiveMarks' => $receiveMarks,
            'authorMarks' => $authorMarks,
            'taskForm' => $taskForm->createView(),
        ]);
    }
}

