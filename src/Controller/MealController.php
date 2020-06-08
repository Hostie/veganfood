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
    * @Route("/meal/{id}/edit", name="meal_edit")
    */
    public function addMeal(Meal $meal = null,Request $request, UserInterface $user){
        if(!$meal){
            $meal = new Meal;
        }
       

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
           
            
        }

        return $this -> render('meal/add.html.twig', [
            'MealForm' => $form -> createView()
        ]);
    }

    /**
    * @Route("meal/{id}/delete", name="meal_delete")
    */
   public function deleteMeal($id){
       
       $manager = $this -> getDoctrine() -> getManager();
       $meal = $manager -> find(Meal::class, $id);
       $manager -> remove($meal);
       $manager -> flush();


       $repository = $this -> getDoctrine()-> getRepository(Meal::class);
       $meal = $repository ->findAll();

       $this -> addFlash('Suppresion', 'Plat : '. $id. 'supprimé');
       return $this ->redirectToRoute('show', ['id' => $id]);
            
       return $this -> render('restaurant/show.html.twig',[
           'meals' => $meal
       ]);
   }

}