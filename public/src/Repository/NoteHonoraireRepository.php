<?php

namespace App\Repository;

use App\Entity\NoteHonoraire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NoteHonoraire>
 *
 * @method NoteHonoraire|null find($id, $lockMode = null, $lockVersion = null)
 * @method NoteHonoraire|null findOneBy(array $criteria, array $orderBy = null)
 * @method NoteHonoraire[]    findAll()
 * @method NoteHonoraire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteHonoraireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NoteHonoraire::class);
    }

    //    /**
    //     * @return NoteHonoraire[] Returns an array of NoteHonoraire objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('n.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?NoteHonoraire
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
