<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\GoalService;
use App\Form\GoalType;
use App\Entity\Goal;

class GoalController extends AbstractController
{
    public function create(Request $request, GoalService $goalService)
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_CREATE_OWN_GOAL, $this->getUser());

        $goal = new Goal();
        
        $form = $this->createForm(GoalType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $goal = $form->getData();

            try {
                $goalService->save($this->getUser(), $goal);
                $this->addFlash('success', 'Goal has been created');
                return $this->redirectToRoute('app_dashboard');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Something went wrong while creating goal');
                return $this->redirectToRoute('app_dashboard');
            }
        }

        return new Response('Form has not been submitted');
    }
}

