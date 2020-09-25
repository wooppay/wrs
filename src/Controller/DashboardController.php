<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserReportType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use App\Enum\PermissionEnum;
use App\Service\TaskService;
use App\Service\RateInfoService;
use App\Service\UserService;
use App\Service\TeamService;
use App\Entity\Task;
use App\Entity\Project;
use App\Entity\Team;
use App\Form\TaskType;
use App\Enum\RoleEnum;
use App\Form\ProjectType;
use App\Form\TeamType;
use App\Service\RoleService;

class DashboardController extends AbstractController
{
    public function main(RoleService $roleService, Security $security, TaskService $taskService, RateInfoService $rateInfoService, UserService $userService, TeamService $teamService)
    {
        $user = $this->getUser();
        $tasks = $taskService->tasksForDashboardByUser($user);
        $receiveMarks = count($user->getRates());
        $authorMarks = count($user->getAuthorRates());
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

	    $userReportForm = $this->createForm(UserReportType::class);

        return $this->render('dashboard/main.html.twig', [
            'tasks' => $tasks,
            'teamForm' => $teamForm->createView(),
            'receiveMarks' => $receiveMarks,
            'authorMarks' => $authorMarks,
            'taskForm' => $taskForm->createView(),
            'projectForm' => $projectForm->createView(),
	        'userReportForm' => $userReportForm->createView()
        ]);
    }

}

