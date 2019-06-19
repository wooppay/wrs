<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Service\TaskService;
use App\Service\SkillService;
use App\Enum\PermissionEnum;
use App\Enum\PermissionMarkEnum;
use App\Service\SecurityService;

class MarkController extends Controller
{
    public function checkList(Request $request, TaskService $taskService, SkillService $skillService, SecurityService $security)
    {
        $user = $this->getUser();
        $task = $taskService->oneById((int) $request->get('id'));
        $skills = [];
        $executor = $task->getExecutor();
        
        if ($security->accessMarkProductOwnerByUser($user)) {
            if ($this->isGranted(PermissionEnum::CAN_BE_CUSTOMER, $executor)) {
                $skills = $skillService->executorSkillByTask($task);
            }

            if ($this->isGranted(PermissionEnum::CAN_BE_TEAMLEAD, $executor)) {
                $skills = $skillService->executorSkillByTask($task);
            }

            if ($this->isGranted(PermissionEnum::CAN_BE_DEVELOPER, $executor)) {
                $skills = array_merge(
                    $skillService->executorSkillByTask($task),
                    $skillService->leadSkillByTask($task),
                    $skillService->customerSkillByTask($task)
                );
            }

        }

        if ($security->accessMarkCustomerByUser($user)) {
            $skills = $skillService->executorSkillByTask($task);
        }
        
        if ($security->accessMarkTeamLeadByUser($user)) {
            $skills = array_merge($skillService->executorSkillByTask($task), $skillService->customerSkillByTask($task));
        }
        
        if ($security->accessMarkDeveloperByUser($user)) {
            $skills = array_merge($skillService->leadSkillByTask($task), $skillService->customerSkillByTask($task));
        }
        
        return $this->render('dashboard/mark/check_list.html.twig', [
            'skills' => $skills,
        ]);
    }
}

