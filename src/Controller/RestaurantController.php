<?php

namespace App\Controller;

use App\Entity\Restaurant;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Form\RestaurantFormType;
use App\Form\ZipFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RestaurantController extends AbstractController
{
        /**
     * @Route("/restaurant/all", name="getAllRestaurant")
     */
    public function getAllMessages()
    {

        $repository = $this -> getDoctrine() -> getRepository(Restaurant::class);
        $restaurants = $repository -> findAll();
        return $this->render('restaurant/getRestaurant.html.twig', [
            'restaurants' => $restaurants
        ]);
    }


    /**
     * @Route("/restaurant/{id}", name="show")
     */
    public function getRestaurant($id)
    {
        $repo = $this -> getDoctrine() -> getRepository(Restaurant::class);
        $restaurant = $repo -> find($id);
        
        $meals = $restaurant-> getIdMeal();

        return $this->render('restaurant/show.html.twig', [
            'restaurant' => $restaurant,
            'meals' => $meals
        ]);
    }

         /**
     * @Route("/restaurants ", name="restaurants")
     */
    public function getAllRestaurants()
    {
        ///je recupere tous les infos des restos
        $repository = $this -> getDoctrine() -> getRepository(Restaurant::class);
        $restaurant = $repository -> findAll();

        return $this->render('restaurant/index.html.twig', [
            'restaurants' => $restaurant
        ]);
    }


    /**
     * @Route("/restaurants/create", name="createRestaurant")
     */

    public function createRestaurant(Request $request, UserInterface $user){

        $restaurant = new Restaurant;

        $form = $this -> createForm(RestaurantFormType::Class, $restaurant);

        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form -> isValid()) {

            $manager = $this -> getDoctrine() -> getManager();
            $manager -> persist($restaurant);
            //Ajouter l'id du créateur du restau au restau
            
            $logo = $form['file']->getData();  //Correspond à la photo du restaurant.
            if (is_object($logo))
            {
                $restaurant -> fileUpload();  
            }

            $restaurant-> setIdUser($user);
            $manager -> flush();    
            
            return $this ->redirectToRoute('restaurants');
            
        }

        return $this -> render('restaurant/create.html.twig', [
            'RestaurantForm' => $form -> createView()
        ]);
    }

     



}