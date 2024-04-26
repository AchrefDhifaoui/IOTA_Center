<?php

namespace App\Form;

use App\Entity\Facture;
use App\Entity\FormationAssurer;
use App\Entity\LigneFacture;
use App\Entity\Unite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneFactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite')
            ->add('prixUnitaire')
            ->add('totalHT')
            ->add('designation', EntityType::class, [
                'class' => FormationAssurer::class,
'choice_label' => 'id',
            ])
            ->add('Unite', EntityType::class, [
                'class' => Unite::class,
'choice_label' => 'id',
            ])
            ->add('Facture', EntityType::class, [
                'class' => Facture::class,
'choice_label' => 'id',
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
