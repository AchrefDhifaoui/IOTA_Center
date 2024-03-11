<?php

namespace App\Form;

use App\Entity\Formateur;
use App\Entity\NoteHonoraire;
use App\Entity\ParametreIota;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteHonoraireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'numero',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('date', DateType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'date',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('client', EntityType::class, [
                'class' => ParametreIota::class,
                'choice_label' => 'nom',
                'attr' => ['class' => 'form-control'],
                'label' => 'Client',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('formateur', EntityType::class, [
                'class' => Formateur::class,
                'attr' => ['class' => 'form-control'],
                'label' => 'Formateur',
                'label_attr' => ['class' => 'form-label'],

            ])
            ->add('ligneNoteHonoraires',CollectionType::class,[
                'entry_type'=>LigneNoteHonoraireType::class,
                'entry_options'=>['label'=>false],
                'allow_add'=>true,
                'by_reference' => false,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NoteHonoraire::class,
        ]);
    }
}
