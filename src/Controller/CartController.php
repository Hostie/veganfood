<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Command;
use App\Entity\Meal;
use App\Entity\User;
use App\Entity\Restaurant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\MealRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;

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
    public function add($id,  SessionInterface $session, MealRepository $productRepository) {
        
        //surcouche tableau (add, remove etc trop bien)
        //rec   up du panier, vide à la base 
        $newMealId = $id;

        $panier = $session ->get('panier', []);
        $panierEnrichi = [];
        $RestaurantId = 0;
        foreach($panier as $id => $quantity){
            $panierEnrichi[] = [
                'product' => $productRepository -> find($id),
                'quantity' => $quantity 
            ];
        }

        foreach($panierEnrichi as $item){
            $RestaurantId = $item['product']-> getIdRestaurant()->getId();
        }
        
        $idOfNew = $productRepository-> find($newMealId)->getIdRestaurant()->getId();
        
        if( $RestaurantId == $idOfNew || $RestaurantId == null ){
            //si mon panier contient dejà le produit 
            if(!empty($panier[$newMealId])){
                //tu rajoute 1 à la quantité 
                $panier[$newMealId]++;
                
            }else{
                //sinon tu le set à 1
                $panier[$newMealId] = 1;
            }
            $session ->set('panier', $panier);
        }
        
        else{
            $this->addFlash(
                'notice',
                'Erreur: Les plats ajoutés au panier doivent provenir du même restaurant.'
            );
        }
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
     * @Route("/panier/order", name="cart_order")
     */
    public function order(SessionInterface $session, UserInterface $user, MealRepository $productRepository, MailerInterface $mailer){

        $panier = $session -> get('panier', []);  //Récuperation du panier.

        $panierEnrichi = [];  //Récuperation des plats contenus dans le panier.
        $commandStringForMail = "";
        foreach($panier as $id => $quantity){
            $panierEnrichi[] = [
                'product' => $productRepository -> find($id),
                'quantity' => $quantity 
            ];
        }  
        $total = 0;  //Calcul du cout total du panier et récuperation de l'id du restaurant.
        $idRestaurant = new Restaurant;
        foreach($panierEnrichi as $item){
            $totalItem = $item['product'] -> getPrice() * $item['quantity'];
            $total += $totalItem + 2.5;
            $idRestaurant = $item['product'] -> getIdRestaurant();
            $commandStringForMail = $commandStringForMail . " " . $item['product']-> getName() . " x " . (string)$item['quantity'] . ";";
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
            $command-> setStatus(false);
            $command-> setIdRestaurant2($idRestaurant);
            foreach($panierEnrichi as $item){
                for ( $i = 1; $i <= $item['quantity']; $i++){
                    $repository = $this -> getDoctrine() -> getRepository(Meal::class);
                    $meal = $repository -> find($item['product']->getId());
                    $command-> addIdMeal($meal);
                }
            }
            $manager -> flush(); 

            $currentDate = new \DateTime();
            $currentDate-> add(new \DateInterval("PT1H"));
            $deliveryDate = $currentDate->format('d/m/y H:i');
            $email = (new Email())
            ->from('latambouillerestaurant@gmail.com')
            ->to($user->getEmail())
            ->subject('Confirmation de votre commande La Tambouille.')
            ->text('Nous vous confirmons votre achat pour un total de '. $total . ' euros. Cette commande contient: ' . $commandStringForMail 
            . ' Vous devriez être livré avant ' . $deliveryDate);

            $mailer->send($email);
            
            $panier = $session -> set('panier', []);
        }

        else {
            $this->addFlash(
                'notice',
                'Erreur: Vous manquez de liquidité, veuillez ajuster cela avant de poursuivre.'
            );
        }

        return $this ->redirectToRoute('cart_index');
    }



}