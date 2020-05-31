<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\Meal;
use App\Form\MealFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RateController extends AbstractController{

    /**
     * @Route("/rate/getAllRate/{mealId}", name="getAllRate")
     */
    public function getAllRate($mealId)
    {
        $repo = $this -> getDoctrine() -> getRepository(Meal::class);
        $meal = $repo -> find($mealId);  //Récuperation du 
        
        $rates = $meal-> getIdRates();
        $AverageNoteArray = array();

        foreach ($rates as $value) {
            array_push($AverageNoteArray, $value->getNote());
        }
        $AverageNote = array_sum($AverageNoteArray) / count($AverageNoteArray);
        //$response = new Response(json_encode($rates));    Pour l'instant on fait une route voir si ca marche bien 
        //return $response;

        return $this->render('rate/getAllRate.html.twig', [
            'rates' => $rates,
            'AverageNote' => $AverageNote
        ]);
    }
}