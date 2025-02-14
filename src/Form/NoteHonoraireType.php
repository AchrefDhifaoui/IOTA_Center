<?php

namespace App\Form;

use App\Entity\Formateur;
use App\Entity\NoteHonoraire;
use App\Entity\ParametreIota;
use App\Entity\RS;
use App\Entity\TVA;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\BooleanToStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteHonoraireType extends AbstractType
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
            ->add('date', DateType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'date',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('formateur', EntityType::class, [
                'class' => Formateur::class,
                'attr' => ['class' => 'form-control'],
                'label' => 'Formateur',
                'label_attr' => ['class' => 'form-label'],

            ])
            ->add('ligneNoteHonoraires',CollectionType::class,[
                'entry_type'=>LigneNoteHonoraireType::class,
                'label' => false,
                'allow_add'=>true,
                'by_reference' => false,
                'allow_delete' => true,
            ])
            ->add('RS', EntityType::class, [
                'class' => RS::class,
                'attr' => ['class' => 'form-select'],
                'label' => 'RS',
                'label_attr' => ['class' => 'form-label'],                // Add any other options you may need
           ]);
        if (!$options['exclude_etat_field']) {
            $builder->add('etat', ChoiceType::class, [
               'choices' => [
                   'Non payé' => NoteHonoraire::ETAT_NON_PAYE,
                   'Payé' => NoteHonoraire::ETAT_PAYE,
                   'Partiellement payé' => NoteHonoraire::ETAT_PARTIELLEMENT_PAYE,
               ],
               'attr' => ['class' => 'form-select'],
                'label' => 'Etat',
                'label_attr' => ['class' => 'form-label'],                // Add any other options you may need
           ]);
        }
//            ->add('etat', ChoiceType::class, [
//                'choices' => [
//                    'Non payé' => NoteHonoraire::ETAT_NON_PAYE,
//                    'Payé' => NoteHonoraire::ETAT_PAYE,
//                    'Partiellement payé' => NoteHonoraire::ETAT_PARTIELLEMENT_PAYE,
//                ],
//                'attr' => ['class' => 'form-control'],
//                'label' => 'Etat',
//                'label_attr' => ['class' => 'form-label'],                // Add any other options you may need
//            ])
//        ->add('RS', EntityType::class, [
//                'class' => RS::class,
//                'attr' => ['class' => 'form-control'],
//                'label' => 'RS',
//                'label_attr' => ['class' => 'form-label'],                // Add any other options you may need
//            ])
           ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NoteHonoraire::class,
            'exclude_etat_field' => false,
        ]);
        $resolver->setAllowedTypes('exclude_etat_field', 'bool');
    }
    private function generateDefaultNumero(): string
    {
        $repository = $this->entityManager->getRepository(NoteHonoraire::class);

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
