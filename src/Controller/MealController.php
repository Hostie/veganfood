<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\Meal;
use App\Form\MealFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class MealController extends AbstractController{


    /**
    * @Route("/meal/add/", name="addMeal")
    */
    public function addMeal(Request $request, UserInterface $user){

        $meal = new Meal;

        $form = $this -> createForm(MealFormType::Class, $meal);

        $form -> handleRequest($request);
        
        if ($form -> isSubmitted() && $form -> isValid()) {

            $manager = $this -> getDoctrine() -> getManager();
            $manager -> persist($meal); 
            
            $photo = $form['file']->getData();
            if (is_object($photo))
            {
                $meal -> fileUpload();
            }

            $repo = $this -> getDoctrine() -> getRepository(Restaurant::class);
            $restaurant = $repo-> find($user->getIdRestaurant());
        
            $meal-> setIdRestaurant($restaurant);  
            $manager -> flush();
            return $this ->redirectToRoute('index');
            
        }

        return $this -> render('meal/add.html.twig', [
            'MealForm' => $form -> createView()
        ]);
    }
}