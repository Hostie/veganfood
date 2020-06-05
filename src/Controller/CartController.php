<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Command;
use App\Entity\Meal;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\MealRepository;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\HttpFoundation\JsonResponse;

class CartController extends AbstractController
{

    /**
     * @Route("/panier", name="cart_index")
     */
    public function index(SessionInterface $session, MealRepository $productRepository){

        $panier = $session -> get('panier', []);

        $panierEnrichi = [];

        foreach($panier as $id => $quantity){
            $panierEnrichi[] = [
                'product' => $productRepository -> find($id),
                'quantity' => $quantity 
            ];
        }
        $total = 0;
        foreach($panierEnrichi as $item){
            $totalItem = $item['product'] -> getPrice() * $item['quantity'];
            $total += $totalItem;
        }


        
        return $this->render('user/panier.html.twig', [
            'items' => $panierEnrichi,
            'total' => $total
        ]);
    }

     /**
     * @Route("/panier/add/{id}", name="cart_add")
     */
    public function add($id,  SessionInterface $session) {
        
        //surcouche tableau (add, remove etc trop bien)
        //rec   up du panier, vide à la base 
        $panier = $session ->get('panier', []);

        //si mon panier contient dejà le produit 
        if(!empty($panier[$id])){
            //tu rajoute 1 à la quantité 
            $panier[$id]++;
        }else{
            //sinon tu le set à 1
            $panier[$id] = 1;
        }
        $session ->set('panier', $panier);

        return $this ->redirectToRoute("cart_index");
    }


     /**
     * @Route("/panier/remove/{id}", name="cart_remove")
     */
    public function remove($id, SessionInterface $session){
        $panier = $session -> get('panier', []);

        if(!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $session -> set('panier', $panier);

        return $this ->redirectToRoute('cart_index');

    }


     /**
     * @Route("/panier/order", name="cart_remove")
     */
    public function order(SessionInterface $session, UserInterface $user, MealRepository $productRepository){

        $panier = $session -> get('panier', []);  //Récuperation du panier.

        $panierEnrichi = [];  //Récuperation des plats contenus dans le panier.
        foreach($panier as $id => $quantity){
            $panierEnrichi[] = [
                'product' => $productRepository -> find($id),
                'quantity' => $quantity 
            ];
        }  

        $total = 0;  //Calcul du cout total du panier.
        foreach($panierEnrichi as $item){
            $totalItem = $item['product'] -> getPrice() * $item['quantity'];
            $total += $totalItem;
        }
        
        if ( $user->getWallet() > $total)  //Si l'utilisateur peut payer.
        {
            $manager = $this -> getDoctrine() -> getManager();

            $repository = $this -> getDoctrine() -> getRepository(User::class);  //Débit sur le compte du client.
            $userForWallet = $repository -> find($user-> getId());
            $userForWallet-> setWallet($userForWallet->getWallet() - $total);
            $manager -> persist($userForWallet);
            
            
            $command = new Command;  //Création de la commande et sauvegarde en db de cette derniere.
            $manager -> persist($command);

            $command-> setDate(new \DateTime());
            $command-> setPrice($total);
            $command-> setIdUser($user);
            //$commandArray = [];
            foreach($panierEnrichi as $item){
                for ( $i = 1; $i <= $item['quantity']; $i++){
                    $repository = $this -> getDoctrine() -> getRepository(Meal::class);
                    $meal = $repository -> find($item['product']->getId());
                    //array_push($commandArray, $meal);
                    $command-> addIdMeal($meal);
                }
            }
            $manager -> flush(); 


            
        }

        else {
            return new JsonResponse("Pas assez de liquidité");
        }
        return $this ->redirectToRoute('cart_index');
        //return $this ->redirectToRoute('restaurants');
        //return new JsonResponse(['commandArray' => $commandArray,
        //    'panier enrichi' => $panierEnrichi,
        //    'total' => $total,
        //    'userId' => $user->getUsername(),
        //    'panier' => $panier
        //]);
    }



}