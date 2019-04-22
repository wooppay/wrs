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
    
    public function team()
    {
        $teams = $this->getDoctrine()
        ->getRepository(Team::class)
        ->findAll();
        
        return $this->render('product/team.html.twig', [
            'teams' => $teams,
        ]);
    }
    
    public function createTeam(Request $request)
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_CREATE_TEAM, $this->getUser());
        
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($team);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_product_panel_team');
        }
        
        return $this->render('product/create_team.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function manage(Request $request)
    {
        $team = $this->getDoctrine()
        ->getRepository(Team::class)
        ->find($request->get('id'));
        
        return $this->render('product/team_manage.html.twig', [
            'team' => $team,
        ]);
    }
    
    public function addMember(Request $request, ProductService $service, SecurityService $security)
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_ADD_MEMBER_TO_TEAM, $this->getUser());
        
        $team = $this->getDoctrine()
        ->getRepository(Team::class)
        ->find($request->get('id'));
        
        $form = $this->createForm(MemberType::class, null, [
            'team' => $team,
            'is_checked' => $request->get('member')['is_leader'] ?? false,
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getDoctrine()->getRepository(User::class)->find(
                $request->request->get('member')['member_id']
            );
            
            // todo transaction
            
            if (!$service->addMemberToTeam($user, $team)) {
                throw new \Exception();
            }
            
            if ($form->getData()['is_leader'] == 'true') {
                $role = $this->getDoctrine()->getRepository(Role::class)->findOneBy([
                    'name' => RoleEnum::TEAM_LEAD,
                ]);
                
                if (!$security->setRoleToUser($user, $role)) {
                    throw new \Exception();
                }
            }
            
            return $this->redirectToRoute('app_product_panel_team_manage', ['id' => $team->getId()]);
        }
        
        return $this->render('product/team_manage_add_member.html.twig', [
            'team' => $team,
            'form' => $form->createView(),
        ]);
    }

    public function deleteTeamMember(Request $request, ProductService $product)
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_DELETE_MEMBER_FROM_TEAM, $this->getUser());
        
        $team = $this->getDoctrine()->getRepository(Team::class)->find(
            $request->get('team_id')
        );
        
        $member = $this->getDoctrine()->getRepository(User::class)->find(
            $request->get('member_id')
        );

        if (!$product->deleteTeamMember($team, $member)) {
            throw new \Exception();
        }

        return $this->redirectToRoute('app_product_panel_team_manage', [
            'id' => $team->getId(),
        ]);
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

