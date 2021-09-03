<?php

namespace App\Repository;

use App\Entity\Filter\SearchProduct;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }


    public function findFilter($price = null, $category = null)
    {
        $query = $this->createQueryBuilder('p')
            ->join('p.category', 'c', 'WITH', 'p.category = c.id');
        if (!empty($price)) {
            $query->andWhere('p.price <= :price')
                ->setParameter('price', $price);
        }
        if ($category) {
            $query->andWhere('c.id = :category')
                ->setParameter('category', $category);
        }

        return $query->getQuery()
            ->getResult();
    }
}
