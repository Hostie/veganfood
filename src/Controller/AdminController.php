<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Restaurant;
use App\Entity\Meal;
use App\Entity\Rate;
use App\Form\SignUpFormType;
use App\Form\RestaurantFormType;
use App\Form\LoginFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function admin(){
        return $this -> render('admin/index.html.twig', [
        ]);
    }

        /**
     * @Route("/admin/restaurant", name="admin_restaurant")
     */
    public function getRestaurant($id)
    {
        $repo = $this -> getDoctrine() -> getRepository(Restaurant::class);
        $restaurant = $repo -> find($id);
        
        $meals = $restaurant-> getIdMeal();

        return $this->render('admin/adminrestaurant.html.twig', [
            'restaurant' => $restaurant,
            'meals' => $meals
        ]);
    }

    /**
     * @Route("/admin/restaurants", name="admin_restaurants")
     */
    public function getAllRestaurants()
    {
       ///je recupere tous les infos des restos
        $repository = $this -> getDoctrine() -> getRepository(Restaurant::class);
        $restaurant = $repository -> findAll();

        $total =0;
        foreach($restaurant as $restaurant)
        {
             $total ++;
        }
      
        $restaurant = $repository -> findAll();
        
        return $this->render('admin/restaurants.html.twig', [
            'restaurants' => $restaurant,
            'total' => $total
        ]);
    }   

     /**
        * @Route("/admin/restaurants/create", name="admin_create_admin")
     * @Route("/admin/restaurants/{id}/edit", name="edit_rest")
     */

    public function createUpdateRestaurant(Restaurant $restaurant = null, Request $request, UserInterface $user){
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
            
            return $this ->redirectToRoute('admin_restaurants');
            
        }

        return $this -> render('restaurant/create.html.twig', [
            'RestaurantForm' => $form -> createView()
        ]);
    }

     /**
     * @Route("admin/restaurants/{id}/delete", name="ad_del_rest")
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


}
