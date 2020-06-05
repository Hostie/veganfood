<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class SignUpFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('firstname')
            ->add('name')
            ->add('password')
            ->add('email')
            ->add('postal')
            ->add('zipcode')
            ->add('phone')
            ->add('file', FileType::Class, array('required' => false))
            ->add('Envoyer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-large btn-info mt-2 mb-2'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
