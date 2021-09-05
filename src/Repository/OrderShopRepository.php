<?php

namespace App\Repository;

use App\Entity\OrderShop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderShop|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderShop|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderShop[]    findAll()
 * @method OrderShop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderShopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderShop::class);
    }

    public function findSuccessOrders($user){
        $qb = $this->createQueryBuilder('o');

        $query = $qb->select('o')
            ->andWhere('o.status > 0')
            ->andWhere('o.user = :currentUser')
            ->setParameter('currentUser', $user)
            ->orderBy('o.id', 'DESC')
            ->getQuery();

        return $query->getResult();
    }

    // /**
    //  * @return Command[] Returns an array of Command objects
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
    public function findOneBySomeField($value): ?Command
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
