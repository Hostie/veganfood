<?php

namespace App\Form;

use App\Entity\Restaurant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class RestaurantFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('address')
            ->add('zippcode')
            ->add('logo', FileType::Class, array('required' => false))
            ->add('description')
            ->add('category')
            >add('photo', FileType::Class, array('required' => false))
            ->add('Ajouter son restaurant', SubmitType::class, [
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
