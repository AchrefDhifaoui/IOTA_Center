<?php

namespace App\Repository;

use App\Entity\Payement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * @extends ServiceEntityRepository<Payement>
 */
class PayementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payement::class);
    }

    //    /**
    //     * @return Payement[] Returns an array of Payement objects
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

    //    public function findOneBySomeField($value): ?Payement
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
        FROM payement
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
    public function getTotalPayementLast12MonthsGrouped(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT DATE_FORMAT(date_payement, "%Y/%m") as month, SUM(montant) as total_montant
        FROM payement
        WHERE date_payement >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY month
        ORDER BY month
    ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchAllAssociative();
    }
    public function getTotalMontantForEspece(): float
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT SUM(p.montant) as total_montant
            FROM payement p
            JOIN mode_payement mp ON p.mode_payement_id = mp.id
            WHERE mp.mode = :mode
        ";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['mode' => 'espece']);

        return (float) $resultSet->fetchOne();
    }
    public function getTotalMontantForCheque(): float
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT SUM(p.montant) as total_montant
            FROM payement p
            JOIN mode_payement mp ON p.mode_payement_id = mp.id
            WHERE mp.mode = :mode
        ";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['mode' => 'cheque']);

        return (float) $resultSet->fetchOne();
    }
    public function getTotalMontantForVirement(): float
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT SUM(p.montant) as total_montant
            FROM payement p
            JOIN mode_payement mp ON p.mode_payement_id = mp.id
            WHERE mp.mode = :mode
        ";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['mode' => 'virement']);

        return (float) $resultSet->fetchOne();
    }




}
