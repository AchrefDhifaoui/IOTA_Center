<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Formateur;
use App\Entity\Formation;
use App\Entity\FormationAssurer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationAssurerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut')
            ->add('unite')
            ->add('quantite')
            ->add('prixUnitaire')
            ->add('formation', EntityType::class, [
                'class' => Formation::class,
'choice_label' => 'id',
            ])
            ->add('formateur', EntityType::class, [
                'class' => Formateur::class,
'choice_label' => 'id',
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
'choice_label' => 'id',
'multiple' => true,
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
