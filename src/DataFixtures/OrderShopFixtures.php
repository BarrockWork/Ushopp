<?php

namespace App\DataFixtures;

use App\Entity\OrderShop;
use App\DataFixtures\datetime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class OrderShopFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 orders! Bam!
//        $dateNow = new \DateTime('now');
//
//        for ($i = 0; $i < 10; $i++) {
//            $order = new OrderShop();
//            $order->setUser();
//            $order->setCreatedAt();
//            $order->setStatus(mt_rand(0, 4));
//            $order->setPaymentAt($dateNow);
//            $order->setDeliveryAddress('100 rue ');
//            $order->setCarrierName('TNT');
//            $order->setCarrierPrice(mt_rand(30, 50));
//            $manager->persist($order);
//        }
//
//        $manager->flush();
    }
}
