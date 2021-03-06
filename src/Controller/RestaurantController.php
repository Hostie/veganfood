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
     * @Route("/panier ", name="panier")
     */
    public function panier()
    {
    

        return $this->render('restaurant/panier.html.twig');
    }


    /**
     * @Route("/restaurants/create", name="createRestaurant")
     * @Route("/admin/restaurants/{id}/edit", name="edit")
     */

    public function createRestaurant(Restaurant $restaurant = null, Request $request, UserInterface $user){
        if(!$restaurant){
            $restaurant = new Restaurant;
        }
       
        $form = $this -> createForm(RestaurantFormType::Class, $restaurant);

        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form -> isValid()) {

            $manager = $this -> getDoctrine() -> getManager();
            $manager -> persist($restaurant);
            
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

     /**
     * @Route("restaurants/{id}/delete", name="deleteRestaurant")
     */
    public function deleteRestaurant($id){
        $manager = $this -> getDoctrine() -> getManager();
        $restaurant = $manager -> find(Restaurant::class, $id);
        $manager -> remove($restaurant);
        $manager -> flush();


        $repository = $this -> getDoctrine()-> getRepository(Restaurant::class);
        $restaurant = $repository ->findAll();

        $this -> addFlash('Suppresion', 'Restaurant : '. $id. 'supprimé');
        return $this -> render('admin/restaurants.html.twig',[
            'restaurants' => $restaurant
        ]);
    }



    /**
     * @Route("/restaurants/zipcode/{code}", name="getRestaurantZipcode")
     */
    public function getRestaurantByZipcode($code){
        
        $repository = $this -> getDoctrine() -> getRepository(Restaurant::class);
        $restaurants = $repository -> findByZipcode($code);

        return $this->render('restaurant/index.html.twig', [
            'restaurants' => $restaurants
        ]);
    }

    

    /**
     * @Route("/restaurants/category/{cat}", name="getRestaurantCategory")
     */
    public function getRestaurantByCategory($cat){
        
        $repository = $this -> getDoctrine() -> getRepository(Restaurant::class);
        $restaurants = $repository -> findByCategory($cat);

        return $this->render('restaurant/index.html.twig', [
            'restaurants' => $restaurants
        ]);
    }


}