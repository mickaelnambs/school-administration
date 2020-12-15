<?php

namespace App\Form;

use App\Entity\Degree;
use App\Entity\Matter;
use App\Form\ApplicationType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatterType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration("Matiere", "Le nom de la matiere ..."))
            ->add('coefficient', IntegerType::class, $this->getConfiguration("Coefficient", "Le coefficient de la matiere ..."))
            ->add('degree', EntityType::class, $this->getConfiguration("Classe", false, ['class' => Degree::class, 'choice_label' => 'name']));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Matter::class,
        ]);
    }
}
