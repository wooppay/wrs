<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\TaskService;
use Symfony\Component\HttpFoundation\Request;
use App\Enum\PermissionEnum;
use App\Service\UserService;
use App\Service\TeamService;
use App\Service\ProjectService;
use App\Entity\Task;
use App\Form\TaskType;

class TaskController extends AbstractController
{
    public function main(TaskService $taskService)
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_SEE_MANAGE_TASK, $this->getUser());
        
        $tasks = $taskService->all();
        
        return $this->render('task/main.html.twig', [
            'tasks' => $tasks,
        ]);
    }
    
    public function detail(Request $request, TaskService $taskService)
    {
        $task = $taskService->oneById(
            (int) $request->get('id')
        );
    }
    
    public function create(Request $request, UserService $userService, TeamService $teamService, ProjectService $projectService, TaskService $taskService)
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_CREATE_TASK, $this->getUser());
        
        $users = $userService->allExceptAdminAndOwner();
        $teams = $teamService->all();
        $projects = $projectService->all();
        
        $task = new Task();
        
        $form = $this->createForm(TaskType::class, $task, [
            'users' => $users,
            'teams' => $teams,
            'projects' => $projects,
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $taskService->create($task);
            
            return $this->redirectToRoute('app_dashboard_task');
        }
        
        return $this->render('task/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
