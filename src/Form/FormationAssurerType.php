<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Formateur;
use App\Entity\Formation;
use App\Entity\FormationAssurer;
use App\Entity\Unite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationAssurerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'date debut',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('unite', EntityType::class, [
                'class' => Unite::class,
                'choice_label' => 'titre',
                'attr' => ['class' => 'form-control'],
                'label' => 'Unite',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('quantite', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'quantité',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('puFormation', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'PU',
                'scale' => 3,
                'html5' => true,
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                'attr' => ['class' => 'form-select'],
                'label' => 'Formation',
                'label_attr' => ['class' => 'form-label'],
                'placeholder' => 'Choisir la formation',
            ])
            ->add('formateur', EntityType::class, [
                'class' => Formateur::class,
                'attr' => ['class' => 'form-select'],
                'label' => 'Formateur',
                'label_attr' => ['class' => 'form-label'],
                'placeholder' => 'Choisir le formateur',

            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'attr' => ['class' => 'form-select'],
                'label_attr' => ['class' => 'form-label'],
                'placeholder' => 'Choisir le client',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FormationAssurer::class,
        ]);
    }
}
