<?php

namespace App\Form;

use App\Entity\Facture;
use App\Entity\FormationAssurer;
use App\Entity\LigneFacture;
use App\Entity\Unite;
use Doctrine\DBAL\Types\StringType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneFactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation', EntityType::class, [
                'class' => FormationAssurer::class,
                'attr' => ['class' => 'form-control text-danger'],

                'label_attr' => ['class' => 'form-label'],
                'placeholder' => 'Choose a formation', // Add a placeholder
                'required' => false,

            ])
            ->add('desManuel',TextType::class,[
'empty_data'=>' ',
                    'attr' => ['class' => 'form-control'],
                    'label'=>'designation Manuel',
                    'label_attr' => ['class' => 'form-label'],



                ]
            )
            ->add('quantite', IntegerType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'quantité',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('prixUnitaire', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'PU',
                'scale' => 3,
                'html5' => true,
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('Unite', EntityType::class, [
                'class' => Unite::class,
                'choice_label' => 'titre',
                'attr' => ['class' => 'form-select'],
                'label' => 'Unite',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('totalHT', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'PTHT',
                'label_attr' => ['class' => 'form-label'],
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LigneFacture::class,
        ]);
    }
}
