<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use App\Enum\PermissionEnum;

class DashboardController extends AbstractController
{
    public function main(Security $security)
    {
        $user = $this->getUser();
        
        if ($security->isGranted(PermissionEnum::CAN_SEE_TASKS_ASSIGNED_TO_ME, $user)) {
            $tasks = $user->getTasks();
        }
        
        if ($security->isGranted(PermissionEnum::CAN_SEE_MY_TEAM_TASKS, $user)) {
            $teams = $user->getTeams();
            
            foreach ($teams as $team) {
                if (!empty($team->getTasks())) {
                    foreach ($team->getTasks() as $task) {
                        $tasks[] = $task;
                    }
                }
            }
        }
        
        return $this->render('dashboard/main.html.twig', [
            'tasks' => $tasks,
        ]);
    }
}

