<?php

namespace App\Repository;

use App\Entity\Facture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Facture>
 *
 * @method Facture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facture[]    findAll()
 * @method Facture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facture::class);
    }

    //    /**
    //     * @return Facture[] Returns an array of Facture objects
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

    //    public function findOneBySomeField($value): ?Facture
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function getTotalDebt(): float
    {
        $qb = $this->createQueryBuilder('f')
            ->select('SUM(f.Total_TTC) as totalDebt')
            ->where('f.etat = :etat')
            ->setParameter('etat', Facture::ETAT_NON_PAYE);

        return (float) $qb->getQuery()->getSingleScalarResult();
    }
    public function getDebtEvolutionLast30Days(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT 
                DATE(f.date_facture) as date,
                SUM(f.Total_TTC) as totalDebt
            FROM 
                facture f
            WHERE 
                f.etat = :etat
            GROUP BY 
                DATE(f.date_facture)
            ORDER BY 
                DATE(f.date_facture) DESC
            LIMIT 30
        ";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['etat' => Facture::ETAT_NON_PAYE]);

        return $resultSet->fetchAllAssociative();
    }
    public function getClientsWithUnpaidInvoices(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT 
                c.id AS client_id, 
                c.nom AS client_name, 
                c.image AS client_image,
                SUM(f.Total_TTC) AS total_debt
            FROM 
                facture f
            JOIN 
                client c ON f.client_id = c.id
            WHERE 
                f.etat = :etat
            GROUP BY 
                c.id, c.nom, c.image
            ORDER BY 
                total_debt DESC
        ";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['etat' => Facture::ETAT_NON_PAYE]);

        return $resultSet->fetchAllAssociative();
    }
}
