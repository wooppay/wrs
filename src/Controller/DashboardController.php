<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use App\Enum\PermissionEnum;

class DashboardController extends AbstractController
{
    public function main(Security $security)
    {
        $tasks = [];
        $user = $this->getUser();
        
        if ($security->isGranted(PermissionEnum::CAN_SEE_TASKS_ASSIGNED_TO_ME, $user)) {
            $tasks = $user->getTasks();
        }
        
        return $this->render('dashboard/main.html.twig', [
            'tasks' => $tasks,
        ]);
    }
}

