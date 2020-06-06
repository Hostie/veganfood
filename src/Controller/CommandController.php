<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\Command;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Form\RestaurantFormType;
use App\Form\ZipFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CommandController extends AbstractController
{
    
    /**
     * @Route("/command/{id}", name="show")
     */
    public function getCommand($id)
    {
        $repo = $this -> getDoctrine() -> getRepository(Command::class);
        $command = $repo -> find($id);
        

        return $this->render('restaurant/show.html.twig', [
            'command' => $restaurant,
        ]);
    }

         /**
     * @Route("/commands ", name="restaurants")
     */
    public function getAllCommands()
    {
        ///je recupere tous les infos des restos
        $repository = $this -> getDoctrine() -> getRepository(Command::class);
        $commands = $repository -> findAll();

        return $this->render('restaurant/index.html.twig', [
            'commands' => $commands
        ]);
    }

     

}