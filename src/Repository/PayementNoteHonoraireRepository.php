<?php

namespace App\Repository;

use App\Entity\PayementNoteHonoraire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PayementNoteHonoraire>
 */
class PayementNoteHonoraireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PayementNoteHonoraire::class);
    }

    //    /**
    //     * @return PayementNoteHonoraire[] Returns an array of PayementNoteHonoraire objects
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

    //    public function findOneBySomeField($value): ?PayementNoteHonoraire
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    /**
     * Calculate the sum of all payment amounts.
     *
     * @return float
     */
    public function getTotalMontant(): float
    {
        $qb = $this->createQueryBuilder('p')
            ->select('SUM(p.montant) as totalMontant')
            ->getQuery();

        $result = $qb->getSingleScalarResult();

        return (float) $result;
    }
    /**
     * Get the total montant for each day in the last 30 days.
     *
     * @return array
     */
    public function getTotalMontantLast30Days(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT DATE(date_payement) as date, SUM(montant) as total_montant
        FROM payement_note_honoraire
        WHERE date_payement >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        GROUP BY DATE(date_payement)
        ORDER BY DATE(date_payement) DESC
    ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        // Using fetchAllAssociative() as it should be available in DBAL 3.8.4
        $result = $resultSet->fetchAllAssociative();
        return $result;
    }
    public function getTotalPayementNoteHonoraireLast12MonthsGrouped(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT DATE_FORMAT(date_payement, "%Y/%m") as month, SUM(montant) as total_montant
        FROM payement_note_honoraire
        WHERE date_payement >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY month
        ORDER BY month
    ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchAllAssociative();
    }

}
