<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\RS;
use App\Entity\Timbre;
use App\Entity\TVA;
use App\Repository\TimbreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', null, [
                'data' => $this->generateDefaultNumero(),
                'attr' => ['class' => 'form-control'],
                'label' => 'numero',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('commentaire', TextType::class, [
                'data' => "--",
                'attr' => ['class' => 'form-control'],
                'label' => 'Commentaire',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('date_facture', DateType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'date',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'attr' => ['class' => 'form-select'],
                'label' => 'Client',
                'label_attr' => ['class' => 'form-label'],

            ])
            ->add('tva', EntityType::class, [
                'class' => TVA::class,
                'attr' => ['class' => 'form-select'],
                'label' => 'TVA',
                'label_attr' => ['class' => 'form-label'],
                'placeholder'=>'choisir taux de TVA'

            ])
            ->add('timbre',EntityType::class, [
                'class'=>Timbre::class,

                'attr' => ['class' => 'form-control'],
                'label' => 'timbre',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('ligneFactures',CollectionType::class,[
                'entry_type'=>LigneFactureType::class,
                'label'=>false,
                'allow_add'=>true,
                'by_reference' => false,
                'allow_delete' => true,
            ])
            ->add('Total_HT',NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Total_HT',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('Total_TVA',NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Total_TVA',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('Total_TTC',NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Total_TTC',
                'label_attr' => ['class' => 'form-label'],
            ])
        ;
        if (!$options['exclude_etat_field']) {
            $builder->add('etat', ChoiceType::class, [
                'choices' => [
                    'Non payé' => Facture::ETAT_NON_PAYE,
                    'Payé' => Facture::ETAT_PAYE,
                    'Partiellement payé' => Facture::ETAT_PARTIELLEMENT_PAYE,
                ],
                'attr' => ['class' => 'form-select'],
                'label' => 'Etat',
                'label_attr' => ['class' => 'form-label'],                // Add any other options you may need
            ]);
        }
        if (!$options['exclude_isConfirmer_field']) {
            $builder->add('confirmed', CheckboxType::class, [
                'attr' => ['class' => 'form-check-input'],
                               'required'=>false
                               // Add any other options you may need
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
            'exclude_etat_field' => false,
            'exclude_isConfirmer_field' => false,
        ]);
        $resolver->setAllowedTypes('exclude_etat_field', 'bool');
        $resolver->setAllowedTypes('exclude_isConfirmer_field', 'bool');
    }
    private function generateDefaultNumero(): string
    {
        $repository = $this->entityManager->getRepository(Facture::class);

        // Count the number of existing NoteHonoraire entities
        $numberOfNotes = $repository->createQueryBuilder('nh')
            ->select('COUNT(nh.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // Generate the default "numero" value based on the count and the current year
        $defaultNumero = $numberOfNotes + 1 . '/' . date('Y');

        return $defaultNumero;
    }
}
