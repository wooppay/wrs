<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Team;
use App\Form\TeamType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\MemberType;
use App\Entity\User;
use App\Service\ProductService;
use App\Enum\PermissionEnum;
use App\Service\SecurityService;
use App\Entity\Role;
use App\Service\ProjectService;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Service\TeamService;
use App\Service\UserService;
use App\Enum\RoleEnum;
use App\Service\SkillService;
use App\Service\RoleService;
use App\Form\SkillType;
use App\Entity\Skill;

class ProductController extends AbstractController
{
    public function index()
    {
        return $this->render('product/main.html.twig');
    }
    
    public function project(Request $request, ProjectService $projectService)
    {
        $projects = $projectService->all();
        
        return $this->render('product/project.html.twig', [
            'projects' => $projects,
        ]);
    }
    
    public function createProject(Request $request, TeamService $teamService, ProjectService $projectService, UserService $userService)
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_CREATE_PROJECT, $this->getUser());
        
        $teams = $teamService->all();
        $customers = $userService->allByRoleName(RoleEnum::CUSTOMER);
        
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project, [
            'teams' => $teams,
            'customers' => $customers,
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $projectService->create($project);
            
            return $this->redirectToRoute('app_product_panel_project');
        }
        
        return $this->render('product/create_project.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

