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
        
        $tasks = $taskService->userCreatedTasks($this->getUser());
        
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

        $task = new Task();

        $form = $this->createForm(TaskType::class, $task, [
            'userService' => $userService,
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setAuthor($this->getUser());
            $taskService->create($task);

            $this->addFlash('success', 'Task was successfully created');
        }

        return $this->redirectToRoute('app_dashboard');
    }

    public function update(Request $request, UserService $userService, TaskService $taskService)
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_UPDATE_TASK, $this->getUser());

        $taskId = $request->get('task_id');
        $task = $taskService->oneById($taskId);

<<<<<<< Updated upstream
=======
        if (!$task) {
            throw $this->createNotFoundException('Task does not exist!');
        }

        if ($taskService->isTaskMarked($task)) {
            throw $this->createNotFoundException('This task has already marked');
        }

        if ($task->getStatus() == TaskEnum::DELETED) {
            throw $this->createNotFoundException('Task was archived');
        }

>>>>>>> Stashed changes
        $form = $this->createForm(TaskType::class, $task, [
            'userService' => $userService,
            'formAction' => 'update'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setAuthor($this->getUser());
            $taskService->create($task);

            $this->addFlash('success', 'Task was successfully updated');

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('dashboard/task/modal/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    public function archive(Request $request, UserService $userService, TaskService $taskService)
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_DELETE_TASK, $this->getUser());
        $taskId = (int) $request->request->get('task_id');
        $task = $taskService->oneById($taskId);

        if (!$task) {
            throw $this->createNotFoundException('Task does not exist!');
        }

        if ($taskService->isTaskMarked($task)) {
            throw $this->createNotFoundException('This task has already marked');
        }

        if ($task->getStatus() == TaskEnum::DELETED) {
            throw $this->createNotFoundException('Task was archived');
        }

        try {
            $task->setStatus(TaskEnum::DELETED);
            $taskService->update($task);
            $this->addFlash('success', 'Task was successfully archived');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Oops, some error has occurred');
            return new JsonResponse([
                'status' => false
            ]);
        }
        
        return new JsonResponse([
            'status' => true
        ]);
    }

    public function teamByProject(Request $request, ProjectService $projectService)
    {
        $projectId = $request->request->get('project_id');
        $project = $projectService->oneById($projectId);
        $team = $project->getTeam();
        
        return new JsonResponse([
            'id' => $team->getId(),
            'name' => $team->getName(),
        ]);
    }
}
