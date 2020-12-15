<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, $this->getConfiguration('Adresse email', 'Votre adresse email ...'))
            ->add('firstName', TextType::class, $this->getConfiguration('Prenom(s)', 'Votre prenom ...'))
            ->add('lastName', TextType::class, $this->getConfiguration('Nom de famille', 'Votre nom de famille ...'))
            ->add('password', PasswordType::class, $this->getConfiguration('Mot de passe', "Votre mot de passe ..."))
            ->add('confirmPassword', PasswordType::class, $this->getConfiguration('Retaper votre mot de passe', 'Confirmation de votre mot de passe ...'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
