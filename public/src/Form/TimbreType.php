<?php

namespace App\Form;

use App\Entity\Timbre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimbreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('taux', IntegerType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'taux',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('date_application', DateType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'date Application',
                'label_attr' => ['class' => 'form-label'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Timbre::class,
        ]);
    }
}
