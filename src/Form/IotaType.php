<?php

namespace App\Form;

use App\Entity\ParametreIota;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class IotaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('logo', FileType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'logo',
                'label_attr' => ['class' => 'form-label'],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Nom',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('adresse', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Adresse',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('telephone', TelType::class, [
                'attr' => ['class' => 'form-control','name'=>'input-mask','data-mask'=>'(+216) 00 000 000','data-mask-visible'=>'true','placeholder'=>'(+216) 00 000 000','autocomplete'=>'off'],
                'label' => 'Telephone',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Email',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('matriculeFiscale', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Matricule Fiscale',
                'label_attr' => ['class' => 'form-label'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParametreIota::class,
        ]);
    }
}
