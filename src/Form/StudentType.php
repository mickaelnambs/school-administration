<?php

namespace App\Form;

use App\Entity\Branch;
use App\Entity\Degree;
use App\Entity\Student;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class StudentType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom", "prénom(s)..."))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom", "Nom de famille ..."))
            ->add('sex', TextType::class, $this->getConfiguration("Sexe", "Masculin ou Feminin ..."))
            ->add('age', IntegerType::class, $this->getConfiguration("Age", "Age de l'etudiant ..."))
            ->add('dateOfBirth', TextType::class, $this->getConfiguration("Date de naissance", "Date et lieu de naissance ..."))
            ->add('phoneNumber', TextType::class, $this->getConfiguration("Tel", "Numero telephone ..."))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Adresse email ..."))
            ->add('address', TextType::class, $this->getConfiguration("Adresse", "Adresse de l'etudiant ..."))
            ->add('image', FileType::class, $this->getConfiguration("Photo", "Photo de l'Etudiant ...", ['data_class' => null]))
            ->add('branch', EntityType::class, $this->getConfiguration("Filiere", false, ['class' => Branch::class, 'choice_label' => 'name']))
            ->add('degree', EntityType::class, $this->getConfiguration("Classe", false, ['class' => Degree::class, 'choice_label' => 'name']));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
