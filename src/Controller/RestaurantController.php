<?php

namespace App\Controller;

use App\Entity\Restaurant;
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

        return $this->render('restaurant/index.html.twig', [
            'restaurants' => $restaurants
        ]);
    }


    /**
     * @Route("/restaurant", name="getRestaurant")
     */
    public function getRestaurant($id)
    {
        $repo = $this -> getDoctrine() -> getRepository(Restaurant::class);
        $restaurant = $repo -> find($id);
        
        return $this->render('restaurant/index.html.twig', [
            'restaurant' => $restaurant
        ]);
    }


    /**
     * @Route("/restaurant/create", name="createRestaurant")
     */

    public function createRestaurant(Request $request, UserInterface $user){

        $restaurant = new Restaurant;

        $form = $this -> createForm(RestaurantFormType::Class, $restaurant);

        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form -> isValid()) {

            $manager = $this -> getDoctrine() -> getManager();
            $manager -> persist($restaurant); 
            //Ajouter l'id du créateur du restau au restau
            
            $logo = $form['logo']->getData();
            if (is_object($logo))
            {
                $restaurant -> fileUpload();  //Faire la fonction nécessaire
            }

            $photo = $form['photo']->getData();
            if (is_object($photo))
            {
                $restaurant -> fileUpload();  //Faire la fonction nécessaire
            }
            

            $manager -> flush();

            //return $this ->redirectToRoute('index');
            
        }
        return $this -> render('restaurant/create.html.twig', [
            'RestaurantForm' => $form -> createView()
        ]);
    }



}