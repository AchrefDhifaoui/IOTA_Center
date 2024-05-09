<?php

namespace App\Form;

use App\Entity\Facture;
use App\Entity\ModePayement;
use App\Entity\Payement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PayementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('datePayement', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'date de payement',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('montant' , null,[
                'attr' => ['class' => 'form-control'],
                'label' => 'Montant',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('numero_cheque_compte',null,[
                'attr' => ['class' => 'form-control'],
                'label' => 'Numero cheque / compte banquaire',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('date_cheque', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'date cheque',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('banque',TextType::class,[
                'attr' => ['class' => 'form-control'],
                'label' => 'Banque',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('modePayement', EntityType::class, [
                'class' => ModePayement::class,
                'attr' => ['class' => 'form-select'],
                'label' => 'Mode de Payement',
                'label_attr' => ['class' => 'form-label'],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payement::class,
        ]);
    }
}
