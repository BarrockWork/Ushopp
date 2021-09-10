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

    public function productFilter($maxPrice = null, $minPrice = null, $category = null, $sortBy = null, $isBest = null)
    {
        $query = $this->createQueryBuilder('p')
            ->join('p.category', 'c', 'WITH', 'p.category = c.id')
            ->andWhere('p.active = TRUE');

        if ($maxPrice) {
            $query->andWhere('p.price <= :maxPrice')
                ->setParameter('maxPrice', $maxPrice);
        }
        if ($minPrice) {
            $query->andWhere('p.price >= :minPrice')
                ->setParameter('minPrice', $minPrice);
        }

        if ($isBest){
            $query->andWhere('p.isBest = 1 ');
        }

        if ($category && $category != 'Tous') {
            $query->andWhere('c.id = :category')
                ->setParameter('category', $category);
        }

        if ($sortBy === 'createdAt') {
            $query->orderBy('p.createdAt', 'DESC');
        }

        if ($sortBy == 'oldCreatedAt') {
            $query->orderBy('p.createdAt', 'ASC');
        }

        if ($sortBy == 'maxPrice') {
            $query->orderBy('p.price', 'DESC');
        }

        if ($sortBy == 'minPrice') {
            $query->orderBy('p.price', 'ASC');
        }

        return $query->getQuery()
            ->getResult();
    }

    public function findByCategoryNameSlug($nameSlug)
    {
        return $this->createQueryBuilder('p')
            ->join('p.category', 'c', 'WITH', 'p.category = c.id')
            ->andWhere('c.nameSlug = :nameSlug')
            ->setParameter('nameSlug', $nameSlug)
            ->getQuery()
            ->getResult();
    }

}
