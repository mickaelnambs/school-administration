<?php

namespace App\Form;

use App\Entity\Professor;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfessorType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom", "prénom(s)..."))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom", "Nom de famille ..."))
            ->add('age', IntegerType::class, $this->getConfiguration("Age", "Age du prof ..."))
            ->add('dateOfBirth', TextType::class, $this->getConfiguration("Date de naissance", "Date et lieu de naissance ..."))
            ->add('phoneNumber', TextType::class, $this->getConfiguration("Tel", "Numero telephone ..."))
            ->add('sex', TextType::class, $this->getConfiguration("Sexe", "Masculin ou Feminin ..."))
            ->add('salary', IntegerType::class, $this->getConfiguration("Salaire", "Salaire ..."))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "L'adresse email du prof ..."))
            ->add('address', TextType::class, $this->getConfiguration("Adresse", "Adresse du prof ..."))
            ->add('image', FileType::class, $this->getConfiguration("Photo", "Photo du prof ...", ['data_class' => null]));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Professor::class,
        ]);
    }
}
