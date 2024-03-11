<?php

namespace App\Repository;

use App\Entity\ParametreIota;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParametreIota>
 *
 * @method ParametreIota|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParametreIota|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParametreIota[]    findAll()
 * @method ParametreIota[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParametreIotaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParametreIota::class);
    }

    //    /**
    //     * @return ParametreIota[] Returns an array of ParametreIota objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ParametreIota
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
