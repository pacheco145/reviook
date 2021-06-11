<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('myAccount');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(AuthenticationUtils $authenticationUtils, Request $request, EntityManagerInterface $doctrine, UserPasswordEncoderInterface $encoder): Response
    {
        
        
        $form = $this->createForm(RegisterFormType::class);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // $user = new User();
            $user = $form->getData();
            $user->setRoles(['ROLE_USER']);
            // $password = $user['password'];
            // $user->setPassword($encoder->encodePassword($user, "1234"));
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));

            $doctrine->persist($user);
            $doctrine->flush();

            $this->addFlash('success', "User created");

            return $this->redirectToRoute("app_login");
        }

        // $doctrine->persist($user);
        // $doctrine->flush();

        // if ($this->getUser()) {
        //     return $this->redirectToRoute('myAccount');
        // }

        // // get the login error if there is one
        // $error = $authenticationUtils->getLastAuthenticationError();
        // // last username entered by the user
        // $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/register.html.twig', ['registerForm'=> $form->createView()]);
    }
}
