<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\DataFixtures\datetime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class OrderFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 orders! Bam!
        $date = '2021-08-25 20:50:31';

        for ($i = 0; $i < 10; $i++) {
            $order = new Order();
            $order->setUser();
            $order->setCreatedAt();
            $order->setStatus(mt_rand(0, 4));
            $order->setPaymentAt(datetime('now'));
            $order->setDeliveryAddress('100 rue ');
            $order->setCarrierName('TNT');
            $order->setCarrierPrice(mt_rand(30, 50));
            $manager->persist($order);
        }

        $manager->flush();
    }
}
