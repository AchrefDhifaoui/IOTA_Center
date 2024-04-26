<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\RS;
use App\Entity\TVA;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
            ->add('date_facture', DateType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'date',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'attr' => ['class' => 'form-control'],
                'label' => 'Client',
                'label_attr' => ['class' => 'form-label'],

            ])
            ->add('tva', EntityType::class, [
                'class' => TVA::class,
                'attr' => ['class' => 'form-select'],
                'label' => 'TVA',
                'label_attr' => ['class' => 'form-label'],

            ])
            ->add('RS', EntityType::class, [
                'class' => RS::class,
                'attr' => ['class' => 'form-select'],
                'label' => 'RS',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('ligneFactures',CollectionType::class,[
                'entry_type'=>LigneFactureType::class,
                'entry_options'=>['label'=>false],
                'allow_add'=>true,
                'by_reference' => false,
                'allow_delete' => true,
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
            'exclude_etat_field' => false,
        ]);
        $resolver->setAllowedTypes('exclude_etat_field', 'bool');
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
