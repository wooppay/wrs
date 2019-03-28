<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Team;
use App\Form\TeamType;
use Symfony\Component\HttpFoundation\Request;

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
}

