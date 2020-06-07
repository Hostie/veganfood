<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Entity\Rate;
use App\Entity\User;
use App\Entity\Restaurant;
use App\Form\LoginFormType;
use App\Form\SignUpFormType;
use App\Form\RestaurantFormType;
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


    //Partie RESTO 

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
        
        $total = 0;
        foreach($restaurant as $restaurant)
        {
             $total ++;
        }
      
        $restaurant = $repository -> findAll();

        return $this->render('admin/restaurants.html.twig', [
            'restaurants' => $restaurant,
            'total' => $total,
                 
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



    //Partie USER


    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function getAllUsers()
    {
       ///je recupere tous les infos des restos
        $repository = $this -> getDoctrine() -> getRepository(User::class);
        $users = $repository -> findAll();

        $total = 0;
        foreach($users as $users)
        {
             $total ++;
        }
        $users = $repository -> findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users,
            'total' => $total
        ]);
    }   

      /**
     * @Route("/admin/user/{id}", name="admin_restaurant")
     */
    public function getUserByID($id)
    {
        $repo = $this -> getDoctrine() -> getRepository(User::class);
        $user = $repo -> find($id);
        

        return $this->render('admin/users.html.twig', [
            'user' => $user
        ]);
    }

      /**
     * @Route("/admin/users/create", name="admin_create_user")
     * @Route("/admin/user/{id}/edit", name="edit_user")
     */
    public function adminCreateUpdateUser(User $usr = null, UserPasswordEncoderInterface $encode, Request $request){
        if(!$usr){
            $usr = new User;
        }
      

        $form = $this -> createForm(SignUpFormType::Class, $usr);
        $form -> handleRequest($request);
        
        if ($form -> isSubmitted() && $form -> isValid()) {

            $manager = $this -> getDoctrine() -> getManager();
            $manager -> persist($usr); // Enregistre l'objet dans le systeme (pas dans la BDD)
            $usr -> setRole('ROLE_USER');

            $file = $form['file']->getData();    //Si le champ est vide on considère que la photo du user sera la photo par défault.
            if (is_object($file))
            {
                $usr -> fileUpload();  //Renome l'image de l'utilisateur, l'enregistre en BDD et dans le dossier public/img.
            }

            $password = $usr -> getPassword();
            $newPassword = $encode -> encodePassword($usr, $password);

            $usr -> setPassword($newPassword);
            $manager -> flush(); // Enregistre dans la BDD en executant la ou les requêtes enregistrées dans le systeme

            return $this ->redirectToRoute('admin_users');
        }
     
        return $this->render('user/signup.html.twig', [
            'SignUpForm' => $form -> createView()
        ]);

    }


     /**
     * @Route("admin/user/{id}/delete", name="ad_del_user")
     */
    public function deleteUser($id){
        $manager = $this -> getDoctrine() -> getManager();
        $user = $manager -> find(User::class, $id);
        $manager -> remove($user);
        $manager -> flush();


        $repository = $this -> getDoctrine()-> getRepository(User::class);
        $user = $repository ->findAll();

        $this -> addFlash('Suppresion', 'User : '. $id. 'supprimé');
        return $this -> render('admin/user.html.twig',[
            'user' => $user
        ]);
    }


}
