<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Meal;
use App\Entity\Rate;
use App\Entity\Restaurant;
use App\Entity\Command;
use App\Form\SignUpFormType;
use App\Form\SignUpRestaurantFormType;
use App\Form\LoginFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\HttpFoundation\JsonResponse;


class UserController extends AbstractController
{
    /**
     * @Route("/signup", name="sign")
     * @Route("/profile/{id}/edit", name="edit")
     */
    public function signUp(User $usr = null, UserPasswordEncoderInterface $encode, Request $request){
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

            return $this ->redirectToRoute('login');
        }
     
        return $this->render('user/signup.html.twig', [
            'SignUpForm' => $form -> createView()
        ]);

    }

    /**
     * @Route("/sr", name="signsr")
     */
    public function signUpRest(UserPasswordEncoderInterface $encode, Request $request){

        $usr = new User;

        $form = $this -> createForm(SignUpRestaurantFormType::Class, $usr);
        $form -> handleRequest($request);
        
        if ($form -> isSubmitted() && $form -> isValid()) {

            $manager = $this -> getDoctrine() -> getManager();
            $manager -> persist($usr); // Enregistre l'objet dans le systeme (pas dans la BDD)
            $usr -> setRole('ROLE_ADMIN');

            $file = $form['file']->getData();    //Si le champ est vide on considère que la photo du user sera la photo par défault.
            if (is_object($file))
            {
                $usr -> fileUpload();  //Renome l'image de l'utilisateur, l'enregistre en BDD et dans le dossier public/img.
            }

            $password = $usr -> getPassword();
            $newPassword = $encode -> encodePassword($usr, $password);

            $usr -> setPassword($newPassword);
            $manager -> flush(); // Enregistre dans la BDD en executant la ou les requêtes enregistrées dans le systeme

            return $this ->redirectToRoute('login');
        }

        return $this->render('user/signupRestaurant.html.twig', [
            'SignUpRestaurantForm' => $form -> createView()
        ]);

    }


          /**
     * @Route("/", name="index")
     */
    public function index(Request $request, ?UserInterface $user){

        if($request->isMethod('post')){

            $code = $request->request->get("zipcode");

            if ( preg_match ( " /^[0-9]{5,5}$/ " , $code) ){

                return $this ->redirectToRoute('getRestaurantZipcode', ['code' => $code]);
            }
            else{
                $this->addFlash(
                    'notice',
                    'Veuillez entrer un code postale correct.'
                );

                return $this->render('user/index.html.twig', []);
            }
        }

        else{
            if ($user) {

                $repository = $this -> getDoctrine() -> getRepository(Restaurant::class);
                $restaurants = $repository -> findByZipcode($user->getZipcode());

                $restaurantsToDisplay = [];
                $i = 0;

                foreach ($restaurants as $value) {

                    if ( $i < 4){
                        array_push($restaurantsToDisplay, $value);
                    }
                    $i += 1;
                }

                return $this->render('user/index.html.twig', [
                    'restaurants' => $restaurantsToDisplay,
                ]);
            }
            else {
                return $this->render('user/index.html.twig', [
                    'restaurants' => false
                ]);
            }
        }
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
     * @Route("/sign", name="login")
     */
    public function Login(AuthenticationUtils $auth){

        $lastUsername = $auth -> getLastUsername();
    
        $error = $auth -> getLastAuthenticationError();

        if($error){
            $this -> addFlash('errors', 'Erreur d\'identifiant !');
        }

        return $this -> render('user/sign.html.twig', [
            'lastUsername' => $lastUsername
        ]);
    }
    
    /**
     * @Route("/profile", name="profile")
     */
    public function profile(?UserInterface $user){

        $commands = $user->getIdCommand();
        $comments = $user->getRateId();

        return $this->render('user/profile.html.twig', [
            'commands' => $commands,
            'comments' => $comments
        ]);
    }

    /**
    * @Route("/rate/add/{mealId}/{comment}/{note}", name="addRate")
    */
    public function addRate($mealId, $comment, $note, UserInterface $user){

        $rate = new Rate;

        $repo = $this -> getDoctrine() -> getRepository(Meal::class);
        $meal = $repo -> find(intval($mealId));

        $rate-> setIdMeal($meal);  //Liaison avec le repas selectionné.

        $rate-> setUserId($user);  //Liaison avec l'user actuel.

        $rate-> setComment($comment);  //Récuperation du commentaire entré.

        $rate-> setNote(intval($note));  //Récuperation de la note

        $manager = $this -> getDoctrine() -> getManager();
        $manager -> persist($rate); 

        $manager -> flush();

        $response = new Response(json_encode("Comment well added."));

        return $response;
    }
    

     /**
     * @Route("profile{id}/delete", name="delete")
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