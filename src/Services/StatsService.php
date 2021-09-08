<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

class StatsService{

    private $manager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;
    }
    public function getStats(){
        $users = $this->getUsersCount();
        $orders = $this->getOrdersCount();
        $unpaid = $this->getUnpaidCount();
        $allPriceTTC = $this->getAllPriceTTC();

        return compact('users', 'orders', 'unpaid', 'allPriceTTC');

    }
    public function getUsersCount(){
        return $this->manager->createQuery('SELECT count(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getOrdersCount(){
        return $this->manager->createQuery('SELECT count(o) FROM App\Entity\OrderShop o')->getSingleScalarResult();
    }

    public function getUnpaidCount(){
        return $this->manager->createQuery('SELECT count(u) FROM App\Entity\OrderShop u')->getSingleScalarResult();
    }

    public function getAllPriceTTC(){
        return $this->manager->createQuery('SELECT SUM(d.price) FROM App\Entity\OrderDetails d')->getSingleScalarResult();
    }

}