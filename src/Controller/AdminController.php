<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Role;
use App\Form\RoleFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Enum\PermissionEnum;

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
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_CREATE_ROLE, $this->getUser());
        
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
}

