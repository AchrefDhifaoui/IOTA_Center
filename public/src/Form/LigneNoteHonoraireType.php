<?php

namespace App\Form;

use App\Entity\FormationAssurer;
use App\Entity\LigneNoteHonoraire;
use App\Entity\NoteHonoraire;
use App\Entity\Unite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class LigneNoteHonoraireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation', EntityType::class, [
                'class' => FormationAssurer::class,
                'attr' => ['class' => 'form-control'],

                'label_attr' => ['class' => 'form-label'],
                'placeholder' => 'Choisir une formation', // Add a placeholder
                'required' => false,

            ])
            ->add('qantite', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'quantité',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('prixUnitaire', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'PU',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('unite', EntityType::class, [
                'class' => Unite::class,
                'choice_label' => 'titre',
                'attr' => ['class' => 'form-control'],
                'label' => 'Unite',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('prixTotalHT', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'PTHT',
                'label_attr' => ['class' => 'form-label'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LigneNoteHonoraire::class,
        ]);

    }




}
