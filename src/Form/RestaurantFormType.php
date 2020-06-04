<?php

namespace App\Form;

use App\Entity\Restaurant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RestaurantFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('address')
            ->add('zipcode')
            ->add('file', FileType::Class, array('required' => false))
            ->add('description')
            ->add('category', ChoiceType::class, [
                'choices'  => [
                    'Vegan' => 'Vegan',
                    'Européen'=> 'Européen',
                    'Asiatique' => 'Asiatique',
                    'Américain' => 'Américain',
                    'Français' => 'Français',
                    'Italien' => 'Italien',
                ]])
            ->add('Ajoutay', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-large btn-info mt-2 mb-2'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Restaurant::class,
        ]);
    }
}
