<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Role;
use App\Form\RoleFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Enum\PermissionEnum;
use App\Entity\Permission;
use App\Form\PermissionFormType;
use App\Form\PermissionAttachType;
use App\Service\SecurityService;
use App\Entity\User;
use App\Enum\UserEnum;
use App\Form\RoleAttachType;
use App\Service\RoleService;
use App\Service\PermissionService;
use App\Service\UserService;
use App\Service\SkillService;
use App\Entity\Skill;
use App\Form\SkillType;

class AdminController extends AbstractController
{
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }

    public function security()
    {
        return $this->render('admin/security.html.twig');
    }
    
    public function role(RoleService $roleService)
    {
        $roles = $roleService->all();
        
        return $this->render('admin/role.html.twig', [
            'roles' => $roles,
        ]);
    }
    
    public function createRole(Request $request, RoleService $roleService)
    {
        $role = new Role();
        $form = $this->createForm(RoleFormType::class, $role);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $roleService->create($role);
            
            return $this->redirectToRoute('app_admin_security_role');
        }
        
        return $this->render('admin/create_role.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function permission(PermissionService $permissionService)
    {
        $permissions = $permissionService->all();
        
        return $this->render('admin/permission.html.twig', [
            'permissions' => $permissions,
        ]);
    }
    
    public function createPermission(Request $request, PermissionService $permissionService)
    {
        $permission = new Permission();
        $form = $this->createForm(PermissionFormType::class, $permission);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $permissionService->create($permission);
            
            return $this->redirectToRoute('app_admin_security_permission');
        }
        
        return $this->render('admin/create_permission.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function roleManage(Request $request, RoleService $roleService)
    {
        $role = $roleService->byId((int) $request->get('id'));
        
        return $this->render('admin/role_manage.html.twig', [
            'role' => $role,
        ]);
    }
    
    public function permissionAttach(Request $request, SecurityService $security, PermissionService $permissionService, RoleService $roleService)
    {
        $role = $roleService->byId((int) $request->get('id'));
        
        $form = $this->createForm(PermissionAttachType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            $permission = $permissionService->byId(
                (int) $request->request->get('permission_attach')['permission_id']
            );
            
            if (!$security->setPermissionToRole($role, $permission)) {
                throw new \Exception();
            }
            
            return $this->redirectToRoute('app_admin_security_role_manage', ['id' => $role->getId()]);
        }
        
        return $this->render('admin/role_manage_permission_attach.html.twig', [
            'role' => $role,
            'form' => $form->createView(),
        ]);
    }
    
    public function deletePermission(Request $request, SecurityService $security)
    {
        $security->deletePermissionById((int) $request->get('id'));
        
        return $this->redirectToRoute('app_admin_security_permission');
    }
    
    public function detachPermission(Request $request, SecurityService $security, RoleService $roleService, PermissionService $permissionService)
    {
        $role = $roleService->byId((int) $request->get('role_id'));

        $permission = $permissionService->byId((int) $request->get('permission_id'));
        
        if (!$security->deleteRolePermission($role, $permission)) {
            throw new \Exception();
        }
        
        return $this->redirectToRoute('app_admin_security_role_manage', ['id' => $role->getId()]);
    }
    
    public function userList(UserService $userService)
    {
        $users = $userService->all();
        
        return $this->render('admin/user_list.html.twig', [
            'users' => $users,
        ]);
    }
    
    public function userManage(Request $request, UserService $userService)
    {
        $user = $userService->byId((int) $request->get('id'));
        
        return $this->render('admin/user_manage.html.twig', [
            'user' => $user,
        ]);
    }
    
    public function userActivate(Request $request, UserService $userService)
    {
        $user = $userService->byId((int) $request->get('id'));
        $userService->approve($user);
        
        return $this->redirectToRoute('app_admin_security_user_manage', [
            'id' => $user->getId(),
        ]);
    }
    
    public function userDeactivate(Request $request, UserService $userService)
    {
        $user = $userService->byId((int) $request->get('id'));
        $userService->deactivate($user);
        
        return $this->redirectToRoute('app_admin_security_user_manage', [
            'id' => $user->getId(),
        ]);
    }
    
    public function attachRole(Request $request, SecurityService $security, UserService $userService, RoleService $roleService)
    {
        $user = $userService->byId((int) $request->get('id'));
        
        $form = $this->createForm(RoleAttachType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            $role = $roleService->byId(
                $request->request->get('role_attach')['role_id']
            );
            
            if (!$security->setRoleToUser($user, $role)) {
                throw new \Exception();
            }
            
            return $this->redirectToRoute('app_admin_security_user_manage', ['id' => $user->getId()]);
        }
        
        return $this->render('admin/user_attach_role.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    
    public function skill()
    {
        return $this->render('admin/skill.html.twig');
    }
    
    public function skillSoft(SkillService $skillService)
    {
        $skills = $skillService->allSoft();
        
        return $this->render('admin/skill_soft.html.twig', [
            'skills' => $skills,
        ]);
    }
    
    public function skillTechnical(SkillService $skillService)
    {
        $skills = $skillService->allTechnical();
        
        return $this->render('admin/skill_technical.html.twig', [
            'skills' => $skills,
        ]);
    }
    
    public function skillSoftCreate(Request $request, RoleService $roleService, SkillService $skillService)
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_CREATE_SOFT_SKILL, $this->getUser());
        
        $roles = $roleService->all();
        $skill = new Skill();
        
        $form = $this->createForm(SkillType::class, $skill, [
            'roles' => $roles,
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $skillService->createSoft($skill);
            
            return $this->redirectToRoute('app_admin_skill_soft');
        }
        
        return $this->render('admin/skill_soft_create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function skillTechnicalCreate(Request $request, RoleService $roleService, SkillService $skillService)
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_CREATE_TECHNICAL_SKILL, $this->getUser());
        
        $roles = $roleService->all();
        $skill = new Skill();
        
        $form = $this->createForm(SkillType::class, $skill, [
            'roles' => $roles,
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $skillService->createTechnical($skill);
            
            return $this->redirectToRoute('app_admin_skill_technical');
        }
        
        return $this->render('admin/skill_technical_create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function skillTechnicalDelete(Request $request, SkillService $skillService)
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_DELETE_TECHNICAL_SKILL, $this->getUser());
        
        $skill = $this->getDoctrine()->getRepository(Skill::class)->find(
            $request->get('id')
        );
        
        $skillService->deleteSkill($skill);
        
        return $this->redirectToRoute('app_admin_skill_technical');
    }
    
    public function skillSoftDelete(Request $request, SkillService $skillService)
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_DELETE_SOFT_SKILL, $this->getUser());
        
        $skill = $this->getDoctrine()->getRepository(Skill::class)->find(
            $request->get('id')
        );
        
        $skillService->deleteSkill($skill);
        
        return $this->redirectToRoute('app_admin_skill_soft');
    }
}

