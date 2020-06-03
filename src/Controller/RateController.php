<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\Meal;
use App\Entity\Rate;
use App\Form\MealFormType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\HttpFoundation\JsonResponse;


class RateController extends AbstractController{

    /**
     * @Route("/rate/getAllRate/{mealId}", name="getAllRate")
     */
    public function getAllRate($mealId)
    {
        $repo = $this-> getDoctrine()-> getRepository(Meal::class);
        $meal = $repo-> find(intval($mealId));  //Récuperation du 
        
        $rates = $meal-> getIdRates();

        $averageNoteArray = array();

        foreach ($rates as $value) {
            array_push($averageNoteArray, $value->getNote());
        }
        $averageNote = array_sum($averageNoteArray) / count($averageNoteArray);

        $commentAndRateArray = array();

        foreach ( $rates as $value) {
            array_push($commentAndRateArray, [$value->getId(), $value->getNote(), $value-> getComment(), $value->getUserId()->getUsername()]);
        }

        return $this -> render('rate/getAllRate.html.twig', [
            'rates' => $commentAndRateArray,
            'averageNote' => $averageNote
        ]);

        //return new JsonResponse(['' => $AverageNote]);
    }

}
