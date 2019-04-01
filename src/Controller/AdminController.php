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
    
    public function role()
    {
        $roles = $this->getDoctrine()
        ->getRepository(Role::class)
        ->findAll();
        
        return $this->render('admin/role.html.twig', [
            'roles' => $roles,
        ]);
    }
    
    public function createRole(Request $request)
    {
        $role = new Role();
        $form = $this->createForm(RoleFormType::class, $role);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($role);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_admin_security_role');
        }
        
        return $this->render('admin/create_role.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function permission()
    {
        $permissions = $this->getDoctrine()
        ->getRepository(Permission::class)
        ->findAll();
        
        return $this->render('admin/permission.html.twig', [
            'permissions' => $permissions,
        ]);
    }
    
    public function createPermission(Request $request)
    {
        $permission = new Permission();
        $form = $this->createForm(PermissionFormType::class, $permission);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($permission);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_admin_security_permission');
        }
        
        return $this->render('admin/create_permission.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function roleManage(Request $request)
    {
        $role = $this->getDoctrine()
        ->getRepository(Role::class)
        ->find($request->get('id'));
        
        return $this->render('admin/role_manage.html.twig', [
            'role' => $role,
        ]);
    }
    
    public function permissionAttach(Request $request, SecurityService $security)
    {
        $role = $this->getDoctrine()
        ->getRepository(Role::class)
        ->find($request->get('id'));
        
        $form = $this->createForm(PermissionAttachType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            $permission = $this->getDoctrine()->getRepository(Permission::class)->find(
                $request->request->get('permission_attach')['permission_id']
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
        $security->deletePermissionById($request->get('id'));
        
        return $this->redirectToRoute('app_admin_security_permission');
    }
    
    public function detachPermission(Request $request, SecurityService $security)
    {
        $role = $this->getDoctrine()
        ->getRepository(Role::class)
        ->find($request->get('role_id'));
        
        $permission = $this->getDoctrine()
        ->getRepository(Permission::class)
        ->find($request->get('permission_id'));
        
        if (!$security->deleteRolePermission($role, $permission)) {
            throw new \Exception();
        }
        
        return $this->redirectToRoute('app_admin_security_role_manage', ['id' => $role->getId()]);
    }
    
    public function userList()
    {
        $users = $this->getDoctrine()
        ->getRepository(User::class)
        ->findAll();
        
        return $this->render('admin/user_list.html.twig', [
            'users' => $users,
        ]);
    }
    
    public function userManage(Request $request)
    {
        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($request->get('id'));
        
        return $this->render('admin/user_manage.html.twig', [
            'user' => $user,
        ]);
    }
    
    public function userActivate(Request $request)
    {
        // todo move to service
        $entityManager = $this->getDoctrine()->getManager();
        
        $user = $entityManager
        ->getRepository(User::class)
        ->find($request->get('id'));
        
        $user->setStatus(UserEnum::APPROVED);
        
        $entityManager->flush();
        
        return $this->redirectToRoute('app_admin_security_user_manage', [
            'id' => $user->getId(),
        ]);
    }
    
    public function userDeactivate(Request $request)
    {
        // todo move to service
        $entityManager = $this->getDoctrine()->getManager();
        
        $user = $entityManager
        ->getRepository(User::class)
        ->find($request->get('id'));
        
        $user->setStatus(UserEnum::NOT_APPROVED);
        
        $entityManager->flush();
        
        return $this->redirectToRoute('app_admin_security_user_manage', [
            'id' => $user->getId(),
        ]);
    }
    
    public function attachRole(Request $request, SecurityService $security)
    {
        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($request->get('id'));
        
        $form = $this->createForm(RoleAttachType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            $role = $this->getDoctrine()->getRepository(Role::class)->find(
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
}

