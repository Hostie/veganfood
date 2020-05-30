<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\Meal;
use App\Form\MealFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class MealController extends AbstractController{


    /**
    * @Route("/meal/add/{restaurantId}", name="addMeal")
    */
    public function addMeal(Request $request, $restaurantId){

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
            $restaurant = $repo -> find($restaurantId);
            $meal-> setIdRestaurant($restaurant);  //Liaison au restaurant dont l'id est en url.

            $manager -> flush();
            return $this ->redirectToRoute('createRestaurant');
            
        }

        return $this -> render('meal/add.html.twig', [
            'MealForm' => $form -> createView()
        ]);
    }
}