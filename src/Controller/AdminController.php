<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Role;
use App\Form\RoleFormType;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }
    
    public function role()
    {
        return $this->render('admin/role.html.twig');
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
            
            return $this->redirectToRoute('app_admin_role');
        }
        
        return $this->render('admin/create_role.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

