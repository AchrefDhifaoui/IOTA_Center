<?php

namespace App\Form;

use App\Entity\FactureAchat;
use App\Entity\Fournisseur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FactureAchatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'numero',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('date', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'date',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('TotalHT', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'TotalHT',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('TotalTVA', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'TotalTVA',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('timbre', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'timbre',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('TotalTTC', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'TotalTTC',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('pieceJointe', FileType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'facture pdf',
                'label_attr' => ['class' => 'form-label'],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->add('fournisseur', EntityType::class, [
                'class' => Fournisseur::class,
                'attr' => ['class' => 'form-control'],
                'label' => 'Fournisseur',
                'label_attr' => ['class' => 'form-label'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FactureAchat::class,
        ]);
    }
}
