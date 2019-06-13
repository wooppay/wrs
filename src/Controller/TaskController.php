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
use Symfony\Component\HttpFoundation\JsonResponse;

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
        
        $task = new Task();
        
        $form = $this->createForm(TaskType::class, $task, [
            'users' => $users,
        ]);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task->setAuthor($this->getUser());
            $taskService->create($task);
            
            return $this->redirectToRoute('app_dashboard_task');
        }
        
        return $this->render('task/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function teamByProject(int $project_id, ProjectService $projectService)
    {
        $project = $projectService->oneById($project_id);
        $team = $project->getTeam();
        
        return new JsonResponse([
            'id' => $team->getId(),
            'name' => $team->getName(),
        ]);
    }
}
