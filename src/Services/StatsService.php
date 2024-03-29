<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

class StatsService
{

    private $manager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;
    }

    public function getStats()
    {
        $users = $this->getUsersCount();
        $orders = $this->getOrdersCount();
        $unpaid = $this->getUnpaidCount();
        $allPriceHT = $this->getAllPriceHT();
        $allPriceTTC = $this->getAllPriceTTC();
        $lostOrders = $this->getOrdersLost();

        return compact('users', 'orders', 'unpaid', 'allPriceTTC', 'lostOrders', 'allPriceHT');
    }

    public function getUsersCount()
    {
        return $this->manager->createQuery('SELECT count(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getOrdersCount()
    {
        return $this->manager->createQuery('SELECT count(o) FROM App\Entity\OrderShop o WHERE o.isPaid = true')->getSingleScalarResult();
    }

    public function getUnpaidCount()
    {
        return $this->manager->createQuery('SELECT count(u) FROM App\Entity\OrderShop u WHERE u.isPaid = false')->getSingleScalarResult();
    }

    public function getAllPriceHT()
    {
        return $this->manager->createQuery('SELECT SUM(d.price * d.quantity) FROM App\Entity\OrderDetails AS d INNER JOIN App\Entity\OrderShop as o WITH d.orderShop = o.id WHERE o.status >= 1 AND o.status < 5 AND o.isPaid = 1')->getSingleScalarResult();
    }

    public function getAllPriceTTC()
    {
        return $this->manager->createQuery('SELECT SUM(d.priceTTC) FROM App\Entity\OrderDetails AS d INNER JOIN App\Entity\OrderShop as o WITH d.orderShop = o.id WHERE o.status >= 1 AND o.status < 5 AND o.isPaid = 1')->getSingleScalarResult();
    }

    public function getOrdersLost()
    {
        return $this->manager->createQuery('SELECT count(o) FROM App\Entity\OrderShop o WHERE o.isPaid = 0 AND o.status = 0')->getSingleScalarResult();
    }
}
