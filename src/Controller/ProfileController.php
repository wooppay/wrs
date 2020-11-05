<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Service\UserService;
use App\Form\ProfileInfoType;

class ProfileController extends AbstractController
{
    public function showProfile(int $id, UserService $userService)
    {
        $user = $userService->byId($id);

        return $this->render('dashboard/profile/show_profile.html.twig', [
            'user' => $user
        ]);
    }

    public function editProfile(int $id, Request $request, UserService $userService)
    {
        $user = $userService->byId($id);

        $form = $this->createForm(ProfileInfoType::class, $user->getProfileInfo());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $profileInfo = $form->getData();
        }

        return $this->render('dashboard/profile/edit_profile.html.twig', [
            'form' => $form->createView()
        ]);
    }
}