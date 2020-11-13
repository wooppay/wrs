<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\UserService;
use App\Service\ProfileInfoService;
use App\Form\ProfileInfoType;
use App\Enum\PermissionEnum;

class ProfileController extends AbstractController
{
    public function showProfile(int $id, UserService $userService)
    {
        $user = $userService->byId($id);

        if (!$user) {
            throw $this->createNotFoundException('The user does not exist');
        }

        $goals = $user->getGoals();

        return $this->render('dashboard/profile/show_profile.html.twig', [
            'user' => $user,
            'goals' => $goals
        ]);
    }

    public function editProfile(int $id, Request $request, UserService $userService, ProfileInfoService $profileInfoService)
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_EDIT_MY_PROFILE, $this->getUser());

        $user = $userService->byId($id);

        if (!$user) {
            throw $this->createNotFoundException('The user does not exist');
        }

        if ($user->getId() != $this->getUser()->getId()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(ProfileInfoType::class, $user->getProfileInfo());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $profileInfo = $form->getData();

            try {
                $profileInfoService->save($profileInfo);
                $this->addFlash('success', 'Profile data was changed successfully');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Something happened while updating your data');
            }
        }

        return $this->render('dashboard/profile/edit_profile.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function changeAvatar(Request $request, UserService $userService, ProfileInfoService $profileInfoService)
    {
        $user = $this->getUser();
        $avatar = $request->files->get('avatar');

        try {
            $profileInfoService->changeAvatar($user, $avatar);
            $this->addFlash('success', 'Avatar changed successfully');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Something happened while uploading avatar');
            throw new Exception($e->getMessage());
        }

        return new Response();
    }
}