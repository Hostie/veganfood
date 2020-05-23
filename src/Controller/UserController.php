<?php

namespace App\Controller;

use App\Entity\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class UserController extends AbstractController
{
    /**
     * @Route("/sign", name="sign")
     */
    public function signUp(){

    
        return $this->render('user/SignUp.html.twig', [
            'SignUpForm' => $form -> createView()
        ]);

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function Logout(){}

    /**
     * @Route("/login_check", name="login_check")
     */
    public function LoginCheck(){}


    /**
     * @Route("/", name="login")
     */
    public function Login(AuthenticationUtils $auth){

        $lastUsername = $auth -> getLastUsername();
    
        $error = $auth -> getLastAuthenticationError();

        if($error){
            $this -> addFlash('errors', 'Erreur d\'identifiant !');
        }

        return $this -> render('user/index.html.twig', [
            'lastUsername' => $lastUsername
        ]);
    }
}