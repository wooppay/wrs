<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserReportType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\TaskService;
use App\Service\TeamService;
use App\Service\RoleService;
use App\Service\GoalService;
use App\Entity\Task;
use App\Entity\Project;
use App\Entity\Team;
use App\Form\TaskType;
use App\Form\ProjectType;
use App\Form\TeamType;
use App\Form\GoalType;

class DashboardController extends AbstractController
{
    public function main(TaskService $taskService, TeamService $teamService, RoleService $roleService, GoalService $goalService)
    {
        $user = $this->getUser();
        $roleTitles = $roleService->allTitlesByUser($user);

        $tasks = $taskService->tasksForDashboardByUser($user);
        $archivedTasks = $taskService->archivedTasks($user);

        $receiveMarks = $user->getRates()->count();
        $authorMarks = $user->getAuthorRates()->count();
        
        $taskForm = $this->createForm(TaskType::class, (new Task()));

        $teams = $teamService->all();

        $projectForm = $this->createForm(ProjectType::class, (new Project()), [
            'teams' => $teams,
        ]);

        $teamForm = $this->createForm(TeamType::class, (new Team()));

        $userReportForm = $this->createForm(UserReportType::class);
        
        $goals = $goalService->byUser($user);
        $goalForm = $this->createForm(GoalType::class);

        return $this->render('dashboard/main.html.twig', [
            'roleTitles' => $roleTitles,
            'tasks' => $tasks,
            'archivedTasks' => $archivedTasks,
            'teamForm' => $teamForm->createView(),
            'receiveMarks' => $receiveMarks,
            'authorMarks' => $authorMarks,
            'taskForm' => $taskForm->createView(),
            'projectForm' => $projectForm->createView(),
            'userReportForm' => $userReportForm->createView(),
            'goals' => $goals,
            'goalForm' => $goalForm->createView()
        ]);
    }

}

