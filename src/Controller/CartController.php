<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\MealRepository;

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
    // public function order(SessionInterface $session, UserInterface $user){
    //     $panier = $session -> get('panier', []);

    //     $command = new Command;

    //     $command -> setPrice();
    //     $command -> setDate();
    //     $command -> setIdUser();

    //     $manager = $this -> getDoctrine() -> getManager();
    //     $manager -> persist($command);
            
            
    //     $manager -> flush();    
    //     return $this ->redirectToRoute('restaurants');

    // }



}