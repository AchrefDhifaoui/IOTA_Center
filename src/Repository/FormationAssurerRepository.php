<?php

namespace App\Repository;

use App\Entity\FormationAssurer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FormationAssurer>
 *
 * @method FormationAssurer|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormationAssurer|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormationAssurer[]    findAll()
 * @method FormationAssurer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationAssurerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormationAssurer::class);
    }

    //    /**
    //     * @return FormationAssurer[] Returns an array of FormationAssurer objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?FormationAssurer
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
