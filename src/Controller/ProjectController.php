<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ProjectService;
use App\Service\TeamService;
use App\Service\UserService;
use App\Enum\PermissionEnum;
use App\Enum\RoleEnum;
use App\Entity\Project;
use App\Form\ProjectType;

class ProjectController extends Controller
{
    public function main(Request $request, ProjectService $projectService)
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_SEE_MANAGE_PROJECT, $this->getUser());
        
        $projects = $projectService->all();
        
        return $this->render('dashboard/project/main.html.twig', [
            'projects' => $projects,
        ]);
    }
    
    public function create(Request $request, TeamService $teamService, ProjectService $projectService, UserService $userService)
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_CREATE_PROJECT, $this->getUser());
        
        $teams = $teamService->all();
        $customers = $userService->allByRoleName(RoleEnum::CUSTOMER);
        
        $project = (new Project())
            ->setOwner($this->getUser())
        ;

        $form = $this->createForm(ProjectType::class, $project, [
            'teams' => $teams,
            'customers' => $customers,
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $projectService->create($project);

            $this->addFlash('success', 'Project was successfully created');
        }

        return $this->redirectToRoute('app_dashboard');
    }
}

