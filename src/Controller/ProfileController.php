<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\UserService;
use App\Service\ProfileInfoService;
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

    public function editProfile(int $id, Request $request, UserService $userService, ProfileInfoService $profileInfoService)
    {
        $user = $userService->byId($id);

        $form = $this->createForm(ProfileInfoType::class, $user->getProfileInfo());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $profileInfo = $form->getData();

            try {
                $profileInfoService->flush($profileInfo);
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
        $profileInfo = $user->getProfileInfo();
        $avatar = $request->files->get('avatar');
        $avatarFilename = $user->getEmail().'-avatar.'.$avatar->guessExtension();

        try {
            $avatar->move(
                $this->getParameter('avatar_directory'),
                $avatarFilename
            );

            $profileInfo->setAvatar($avatarFilename);
            $profileInfoService->flush($profileInfo);

            $this->addFlash('success', 'Avatar changed successfully');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Something happened while uploading avatar');
            throw new Exception($e->getMessage());
        }

        return new Response();
    }
}