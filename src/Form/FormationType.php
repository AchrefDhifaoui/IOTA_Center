<?php

namespace App\Form;

use App\Entity\Domaine;
use App\Entity\Formation;
use App\Entity\Mode;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Titre',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('detail', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Detail',
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
            ->add('objectifs', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'objectifs',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('contenu', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'contenu',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('prix', IntegerType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'prix',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('duree', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'duree',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('mode', EntityType::class, [
//                'attr' => ['class' => 'form-selectgroup-input','type'=>'checkbox' ,'style' => 'display: none','name'=>'name'],
                'class' => Mode::class,
                'choice_label' => 'mode',

                'expanded' => true,
                'multiple' => true,


            ])
            ->add('domaine', EntityType::class, [
                'attr' => ['class' => 'form-select'],
                'label' => 'domaine',
                'label_attr' => ['class' => 'form-label'],
                'class' => Domaine::class,
'choice_label' => 'titre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
