<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Service\TaskService;
use App\Service\SkillService;

class MarkController extends Controller
{
    public function checkList(Request $request, TaskService $taskService, SkillService $skillService)
    {
        $task = $taskService->oneById((int) $request->get('id'));
        $skills = $skillService->executorSkillByTask($task);

        return $this->render('mark/check_list.html.twig', [
            'skills' => $skills,
        ]);
    }
}

