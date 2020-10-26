<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\TaskService;
use App\Service\UserService;
use App\Service\TeamService;
use App\Entity\Task;
use App\Entity\Project;
use App\Entity\Team;
use App\Form\TaskType;
use App\Form\ProjectType;
use App\Form\TeamType;
use App\Service\RoleService;

class DashboardController extends AbstractController
{
    public function main(RoleService $roleService, TaskService $taskService, UserService $userService, TeamService $teamService)
    {
        $user = $this->getUser();

        $tasks = $taskService->tasksForDashboardByUser($user);

        $receiveMarks = $user->getRates()->count();
        $authorMarks = $user->getAuthorRates()->count();

        $tasksUsers = $userService->allApprovedExceptAdminAndOwnerAndCustomer();
        $taskForm = $this->createForm(TaskType::class, (new Task()), [
            'users' => $tasksUsers,
        ]);

        $projectCustomers = $userService->allByRoleName($roleService->getRoleCustomer()->getName());
        $teams = $teamService->all();
        $projectForm = $this->createForm(ProjectType::class, (new Project()), [
            'teams' => $teams,
            'customers' => $projectCustomers,
        ]);

        $teamForm = $this->createForm(TeamType::class, (new Team()));

        return $this->render('dashboard/main.html.twig', [
            'tasks' => $tasks,
            'teamForm' => $teamForm->createView(),
            'receiveMarks' => $receiveMarks,
            'authorMarks' => $authorMarks,
            'taskForm' => $taskForm->createView(),
            'projectForm' => $projectForm->createView(),
        ]);
    }
}

