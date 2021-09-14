<?php

namespace App\Repository;

use App\Entity\OrderDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderDetails[]    findAll()
 * @method OrderDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderDetails::class);
    }

    public function getProductsOfMonths() {
        $query = $this->createQueryBuilder('o')
            ->select('o.id','SUM(o.quantity) AS quantity', 'p.name', 'os.paymentAt')
            ->join('o.orderShop', 'os',  'WITH', 'o.orderShop = os.id')
            ->innerJoin('o.product', 'p',  'WITH', 'o.product = p.id')
            ->groupBy('p.id')
            ->andWhere('os.isPaid >= 1')
            ->andWhere('os.status >= 1')
            ->andWhere('os.paymentAt >= :lastMonth')
            ->orderBy('os.paymentAt')
            ->setParameter('lastMonth', new \DateTime('-1 month'))
            ->getQuery()
        ;

        return $query->getResult();
    }

    public function getRevenuesLast3Months() {
        $query = $this->createQueryBuilder('o')
            ->select('o.id','SUM(o.priceTTC) AS revenues', 'os.paymentAt')
            ->join('o.orderShop', 'os',  'WITH', 'o.orderShop = os.id')
            ->groupBy('os.id')
            ->andWhere('os.isPaid >= 1')
            ->andWhere('os.status >= 1')
            ->andWhere('os.paymentAt >= :lastMonth')
            ->orderBy('os.paymentAt')
            ->setParameter('lastMonth', new \DateTime('-3 month'))
            ->getQuery()
        ;

        return $query->getResult();
    }

    public function getRevenuesPerCategory() {
        $query = $this->createQueryBuilder('o')
            ->select('o.id','SUM(o.priceTTC) AS revenues', 'c.name')
            ->join('o.orderShop', 'os',  'WITH', 'o.orderShop = os.id')
            ->innerJoin('o.product', 'p',  'WITH', 'o.product = p.id')
            ->innerJoin('p.category', 'c',  'WITH', 'p.category = c.id')
            ->groupBy('p.category')
            ->andWhere('os.isPaid >= 1')
            ->andWhere('os.status >= 1')
            ->andWhere('os.paymentAt >= :lastMonth')
            ->orderBy('os.paymentAt')
            ->setParameter('lastMonth', new \DateTime('-3 month'))
            ->getQuery()
        ;

        return $query->getResult();
    }


    // /**
    //  * @return CommandDetail[] Returns an array of CommandDetail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommandDetail
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
