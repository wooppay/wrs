<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Service\TaskService;
use App\Service\SkillService;
use App\Enum\PermissionEnum;
use App\Enum\PermissionMarkEnum;
use App\Service\SecurityService;
use App\Form\CheckListType;
use App\Service\RateInfoService;

class MarkController extends Controller
{
    public function checkList(Request $request, TaskService $taskService, SkillService $skillService, SecurityService $security, RateInfoService $rateInfoService)
    {
        $user = $this->getUser();
        $task = $taskService->oneById((int) $request->get('id'));
        $skills = [];
        $executor = $task->getExecutor();
        
        if ($security->accessMarkProductOwnerByUser($user)) {
            $skills = $skillService->skillsAuthorProductOwnerByTask($task);
        }

        if ($security->accessMarkCustomerByUser($user) && empty($skills)) {
            $skills = $skillService->skillsAuthorCustomerByTask($task);
        }
        
        if ($security->accessMarkTeamLeadByUser($user) && empty($skills)) {
            $skills = $skillService->skillsAuthorTeamLeadByTask($task);
        }
        
        if ($security->accessMarkDeveloperByUser($user) && empty($skills)) {
            $skills = $skillService->skillsAuthorDeveloperByTask($task);
        }
        
        $form = $this->createForm(CheckListType::class, null, [
            'skills' => $skills,
            'task' => $task,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $res = $rateInfoService->prepareData($form->getData(), $task, $user);
            $rateInfoService->createByCheckList($res);

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('dashboard/mark/check_list.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    public function historyIncoming(RateInfoService $rateInfoService)
    {
        $previews = $rateInfoService->incomingByUserGroupByTask($this->getUser());
        
        return $this->render('dashboard/mark/history/main.html.twig', [
            'previews' => $previews,
        ]);
    }

    public function historyOutcoming(RateInfoService $rateInfoService)
    {
        $previews = $rateInfoService->outcomingByUserGroupByTask($this->getUser());
        
        return $this->render('dashboard/mark/history/outcoming_main.html.twig', [
            'previews' => $previews,
        ]);
    }


    public function historyIncomingDetail(Request $request, TaskService $taskService, RateInfoService $rateInfoService)
    {
        $task = $taskService->oneById(
            $request->get('id')
        );

        $rates = $rateInfoService->incomingByUserAndTaskGroupByAuthorAndMarks($this->getUser(), $task);
        
        return $this->render('dashboard/mark/history/incoming_detail.html.twig', [
            'task' => $task,
            'rates' => $rates,
        ]);
    }
}

