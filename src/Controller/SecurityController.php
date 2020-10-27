<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordRecoveryType;
use App\Form\RecoveryEmailType;
use App\Service\UserService;
use App\Service\MailerService;
use App\Service\PasswordRecoveryService;
use Symfony\Component\Form\FormError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecurityController extends AbstractController
{
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    public function passwordRecovery(Request $request, \Swift_Mailer $mailer, UserService $userService, MailerService $mailerService, PasswordRecoveryService $passwordRecoveryService)
    {
        $submitted = false;
        $form = $this->createForm(RecoveryEmailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];
            $user = $userService->byEmail($email);

            if (!$user) {
                $form->get('email')->addError(new FormError('User with this mail was not found'));

                return $this->render('security/password_recovery.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            $token = sha1(uniqid($email));
            $passwordRecoveryService->updateTokenByEmail($email, $token);

            $url = $this->generateUrl('app_password_recovery_confirm', [
                'email' => $email,
                'token' => $token
            ], UrlGeneratorInterface::ABSOLUTE_URL);

            $body = $this->renderView('security/recovery_email_body.html.twig', [
                'url' => $url
            ]);
            $submitted = $mailerService->sendMessage('Password Recovery', $email, $body);
        }

        return $this->render('security/password_recovery.html.twig', [
            'form' => $form->createView(),
            'submitted' => $submitted
        ]);
    }

    public function confirmPasswordRecovery(Request $request, UserService $userService, UserPasswordEncoderInterface $encoder, PasswordRecoveryService $passwordRecoveryService)
    {
        $email = $request->query->get('email');
        $token = $request->query->get('token');

        if (!$email || !$token) {
            throw $this->createNotFoundException();
        }

        $passwordRecovery = $passwordRecoveryService->getOneByEmail($email);

        if (!$passwordRecovery) {
            throw $this->createNotFoundException();
        }

        if ($passwordRecovery->getToken() != $token) {
            throw $this->createNotFoundException();
        }

        if ($passwordRecoveryService->isTokenExpired($passwordRecovery)) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(PasswordRecoveryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $userService->byEmail($email);
            $newPassword = $encoder->encodePassword($user, $data['password']);

            $user->setPassword($newPassword);
            $userService->save($user);

            $this->addFlash('success', 'Password was successfully changed');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/confirm_password_recovery.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
