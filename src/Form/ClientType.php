<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\FormationAssurer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Nom',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('adresse', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'adresse',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('telephone', TextType::class, [
                'attr' => ['class' => 'form-control','name'=>'input-mask','data-mask'=>'00000000','data-mask-visible'=>'true','placeholder'=>'00000000','autocomplete'=>'off'],
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
                'label' => 'MatriculeFiscale',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('image', FileType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'image',
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
//            ->add('formationAssurers', EntityType::class, [
//                'class' => FormationAssurer::class,
//'choice_label' => 'id',
//'multiple' => true,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
