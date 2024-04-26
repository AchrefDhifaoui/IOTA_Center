<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\RS;
use App\Entity\TVA;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero')
            ->add('date_facture')
            ->add('etat')
            ->add('client', EntityType::class, [
                'class' => Client::class,
'choice_label' => 'id',
            ])
            ->add('tva', EntityType::class, [
                'class' => TVA::class,
'choice_label' => 'id',
            ])
            ->add('RS', EntityType::class, [
                'class' => RS::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
