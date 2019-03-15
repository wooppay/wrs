<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Role;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\RoleRepository;
use App\Service\SecurityService;

class RegistrationController extends AbstractController
{

    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, SecurityService $security): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            
            $role = $this->getDoctrine()
            ->getRepository(Role::class)
            ->findOneByName('ROLE_USER');
            
            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->getConnection()->beginTransaction();
            
            $entityManager->persist($user);
            $entityManager->flush();
            
            if (!$security->setRoleToUser($user, $role)) {
                $entityManager->getConnection()->rollBack();
                throw new \Exception();
            }
            
            $entityManager->getConnection()->commit();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('index');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
