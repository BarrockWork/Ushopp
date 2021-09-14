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
        $allPriceTTC = $this->getAllPriceTTC();
        $lostOrders = $this->getOrdersLost();

        return compact('users', 'orders', 'unpaid', 'allPriceTTC', 'lostOrders');

    }

    public function getUsersCount()
    {
        return $this->manager->createQuery('SELECT count(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getOrdersCount()
    {
        return $this->manager->createQuery('SELECT count(o) FROM App\Entity\OrderShop o')->getSingleScalarResult();
    }

    public function getUnpaidCount()
    {
        return $this->manager->createQuery('SELECT count(u) FROM App\Entity\OrderShop u WHERE u.isPaid = false')->getSingleScalarResult();
    }

    public function getAllPriceTTC()
    {
        return $this->manager->createQuery('SELECT SUM(d.price) FROM App\Entity\OrderDetails AS d INNER JOIN App\Entity\OrderShop as o WITH d.orderShop = o.id WHERE o.status >= 1 AND o.isPaid = 1')->getSingleScalarResult();
    }

    public function getOrdersLost()
    {
        return $this->manager->createQuery('SELECT count(o) FROM App\Entity\OrderShop o WHERE o.isPaid = 0 AND o.status = 0')->getSingleScalarResult();

    }
}