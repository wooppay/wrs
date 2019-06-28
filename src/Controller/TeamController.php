<?php
namespace App\Controller;

use App\Service\RoleService;
use App\Service\UserService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Team;
use Symfony\Component\HttpFoundation\Request;
use App\Enum\PermissionEnum;
use App\Form\TeamType;
use App\Service\ProductService;
use App\Service\SecurityService;
use App\Form\MemberType;
use App\Enum\RoleEnum;
use App\Entity\User;
use App\Entity\Role;
use App\Service\TeamService;

class TeamController extends Controller
{
    public function main()
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_SEE_MANAGE_TEAM, $this->getUser());
        
        $teams = $this->getDoctrine()
        ->getRepository(Team::class)
        ->findAll();
        
        return $this->render('dashboard/team/main.html.twig', [
            'teams' => $teams,
        ]);
    }
    
    public function create(Request $request, TeamService $teamService)
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_CREATE_TEAM, $this->getUser());
        
        $team = new Team();
        $team->setOwner($this->getUser());
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $teamService->create($team);

            $this->addFlash('success', 'Team was successfully created');
        }

        return $this->redirectToRoute('app_dashboard');
    }
    
    public function manage(Request $request)
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_SEE_MANAGE_TEAM, $this->getUser());
        
        $team = $this->getDoctrine()
        ->getRepository(Team::class)
        ->find($request->get('id'));

        $memberForm = $this->createForm(MemberType::class, null, [
            'team' => $team,
            'is_checked' => $request->get('member')['is_leader'] ?? false,
        ]);

        return $this->render('dashboard/team/manage.html.twig', [
            'team' => $team,
            'memberForm' => $memberForm->createView(),
        ]);
    }
    
    public function addMember(Request $request, ProductService $service, SecurityService $security, RoleService $roleService)
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

            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();

            try{
                if (!$service->addMemberToTeam($user, $team)) {
                    throw new \Exception();
                }

                if ($form->getData()['is_leader'] == 'true') {
                    $role = $roleService->getRoleTeamLead();

                    if (!$security->setRoleToUser($user, $role)) {
                        throw new \Exception();
                    }
                }
            }catch(\Exception $e){
                $em->getConnection()->rollBack();
                throw $e;
            }
            $em->commit();

            $this->addFlash('success', 'Memeber was successfully added to team');
        } else {
            $this->addFlash('danger', 'Cannot add member to team');
        }
        
        return $this->redirectToRoute('app_dashboard_team_manage', ['id' => $team->getId()]);
    }
    
    public function deleteTeamMember(Request $request, ProductService $product, SecurityService $securityService, RoleService $roleService)
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_DELETE_MEMBER_FROM_TEAM, $this->getUser());
        
        $team = $this->getDoctrine()->getRepository(Team::class)->find(
            $request->get('team_id')
        );
        
        $member = $this->getDoctrine()->getRepository(User::class)->find(
            $request->get('member_id')
        );

        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();
        try{
            if (!$product->deleteTeamMember($team, $member)) {
                throw new \Exception();
            }

            if ($this->isGranted(PermissionEnum::CAN_BE_TEAMLEAD, $member)) {

                $role = $roleService->getRoleTeamLead();
                $securityService->detachRoleFromUser($member, $role);
            }
        }catch(\Exception $e){
            $em->getConnection()->rollBack();
            throw $e;
        }
        $em->commit();


        $this->addFlash('success', 'Memeber was successfully deleted from team');

        return $this->redirectToRoute('app_dashboard_team_manage', [
            'id' => $team->getId(),
        ]);
    }
}

