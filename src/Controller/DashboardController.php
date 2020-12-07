<?php
namespace App\Controller;

use App\Entity\User;
use App\Enum\PermissionEnum;
use App\Form\UserReportType;
use App\Service\ActivityService;
use App\Twig\ActivityExtension;
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
use Symfony\Component\HttpFoundation\JsonResponse;

class DashboardController extends AbstractController
{
	private const ACTIVITY_LIMIT = 5;

    public function main(TaskService $taskService, TeamService $teamService, RoleService $roleService, GoalService $goalService, ActivityService $activityService)
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

        list($activities, $moreRecordsExist) = $activityService->activityByUser($user, self::ACTIVITY_LIMIT);

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
            'goalForm' => $goalForm->createView(),
            'activities' => $activities,
	        'moreRecordsExist' => $moreRecordsExist
        ]);
    }

    public function userActivity(int $offset, ActivityService $activityService)
    {
    	if (!$this->isGranted(PermissionEnum::CAN_SEE_ACTIVITY_IN_DASHBOARD))
	    {
	    	$this->createAccessDeniedException();
	    }

	    list($activities, $isMoreRecordsExist) = $activityService->activityByUser($this->getUser(), self::ACTIVITY_LIMIT, $offset);

	    $activityHtml = $activityService->getPrettyHtmlActivity($activities);

	    return JsonResponse::create(['activitiesHtml' => $activityHtml, 'isMoreRecordExist' => $isMoreRecordsExist]);
    }

}

