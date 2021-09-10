<?php

namespace App\DataFixtures;

use App\Entity\OrderDetails;
use App\Entity\OrderShop;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderShopFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $this->generate5OrderShopsFigurine($manager);
        $this->generate5OrderShopsPoster($manager);
        $this->generate5OrderShopsAccessoire($manager);
        $this->generate5OrderShopsVetement($manager);
        $this->generate5OrderShopsLampe($manager);
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ProductFixtures::class,
        ];
    }

    private function generate5OrderShopsFigurine(ObjectManager $manager){
        $productsFigurine = $manager->getRepository(Product::class)->findByCategoryNameSlug('figurines');
        $userAdmin = $manager->getRepository(User::class)->findOneByEmail('admin@admin.fr');
        $dateNow = new \DateTime('now');
        $reference = $dateNow->format('dmY').'-'.uniqid();

        // Orders with figurines
        $orderShop = new OrderShop();
        $orderShop->setUser($userAdmin);
        $orderShop->setStatus(0);
        $orderShop->setReference($reference);
        $orderShop->setPaymentAt(new \DateTime('now'));
        $orderShop->setDeliveryAddress("LuffyTaro 25 Rue Claude Tillier, 75012 Paris");
        $orderShop->setCarrierName("Colissimo");
        $orderShop->setCarrierPrice(2.99);
        $manager->persist($orderShop);
        $manager->flush();

        foreach ($productsFigurine as $product) {
            $orderDetails = new OrderDetails();
            $orderDetails->setProduct($product);
            $orderDetails->setQuantity(3);
            $orderDetails->setPrice(69.99);
            $orderDetails->setPriceTtc(89.78);
            $orderDetails->setOrderShop($orderShop);
            $manager->persist($orderDetails);
            $manager->flush();
        }

    }

    private function generate5OrderShopsPoster(ObjectManager $manager){
        $productsPoster = $manager->getRepository(Product::class)->findByCategoryNameSlug('posters');
        $userAdmin = $manager->getRepository(User::class)->findOneByEmail('admin@admin.fr');
        $dateNow = new \DateTime('now');
        $reference = $dateNow->format('dmY').'-'.uniqid();

        // Orders with posters
        $orderShop = new OrderShop();
        $orderShop->setUser($userAdmin);
        $orderShop->setStatus(1);
        $orderShop->setReference($reference);
        $orderShop->setPaymentAt(new \DateTime('now'));
        $orderShop->setDeliveryAddress("LuffyTaro 25 Rue Claude Tillier, 75012 Paris");
        $orderShop->setCarrierName("Colissimo");
        $orderShop->setCarrierPrice(2.99);
        $manager->persist($orderShop);
        $manager->flush();

        foreach ($productsPoster as $product) {
            $orderDetails = new OrderDetails();
            $orderDetails->setProduct($product);
            $orderDetails->setQuantity(3);
            $orderDetails->setPrice(69.99);
            $orderDetails->setPriceTtc(121.50);
            $orderDetails->setOrderShop($orderShop);
            $manager->persist($orderDetails);
            $manager->flush();
        }

    }

    private function generate5OrderShopsAccessoire(ObjectManager $manager){
        $productsAccessoires = $manager->getRepository(Product::class)->findByCategoryNameSlug('accessoires');
        $userUser = $manager->getRepository(User::class)->findOneByEmail('user@admin.fr');
        $dateNow = new \DateTime('now');
        $reference = $dateNow->format('dmY').'-'.uniqid();

        // Orders with accessoires
        $orderShop = new OrderShop();
        $orderShop->setUser($userUser);
        $orderShop->setStatus(2);
        $orderShop->setReference($reference);
        $orderShop->setPaymentAt(new \DateTime('now'));
        $orderShop->setDeliveryAddress("ZoroDono 25 Rue Claude Tillier, 75012 Paris");
        $orderShop->setCarrierName("Chronopost");
        $orderShop->setCarrierPrice(4.99);
        $manager->persist($orderShop);
        $manager->flush();

        foreach ($productsAccessoires as $product) {
            $orderDetails = new OrderDetails();
            $orderDetails->setProduct($product);
            $orderDetails->setQuantity(3);
            $orderDetails->setPrice(9.99);
            $orderDetails->setPriceTtc(27.78);
            $orderDetails->setOrderShop($orderShop);
            $manager->persist($orderDetails);
            $manager->flush();
        }

    }

    private function generate5OrderShopsLampe(ObjectManager $manager){
        $productsLampes = $manager->getRepository(Product::class)->findByCategoryNameSlug('lampes');
        $userUser = $manager->getRepository(User::class)->findOneByEmail('user@admin.fr');
        $dateNow = new \DateTime('now');
        $reference = $dateNow->format('dmY').'-'.uniqid();

        // Orders with lampes
        $orderShop = new OrderShop();
        $orderShop->setUser($userUser);
        $orderShop->setReference($reference);
        $orderShop->setStatus(0);
        $orderShop->setPaymentAt(new \DateTime('now'));
        $orderShop->setDeliveryAddress("ZoroDono 25 Rue Claude Tillier, 75012 Paris");
        $orderShop->setCarrierName("Chronopost");
        $orderShop->setCarrierPrice(4.99);
        $manager->persist($orderShop);
        $manager->flush();

        foreach ($productsLampes as $product) {
            $orderDetails = new OrderDetails();
            $orderDetails->setProduct($product);
            $orderDetails->setQuantity(3);
            $orderDetails->setPrice(49.99);
            $orderDetails->setPriceTtc(150.09);
            $orderDetails->setOrderShop($orderShop);
            $manager->persist($orderDetails);
            $manager->flush();
        }

    }

    private function generate5OrderShopsVetement(ObjectManager $manager){
        $productsVetements = $manager->getRepository(Product::class)->findByCategoryNameSlug('vetements');
        $userAdmin = $manager->getRepository(User::class)->findOneByEmail('admin@admin.fr');
        $dateNow = new \DateTime('now');
        $reference = $dateNow->format('dmY').'-'.uniqid();

        // Orders with vetements
        $orderShop = new OrderShop();
        $orderShop->setUser($userAdmin);
        $orderShop->setStatus(0);
        $orderShop->setReference($reference);
        $orderShop->setPaymentAt(new \DateTime('now'));
        $orderShop->setDeliveryAddress("LuffyTaro 25 Rue Claude Tillier, 75012 Paris");
        $orderShop->setCarrierName("UPS");
        $orderShop->setCarrierPrice(5.99);
        $manager->persist($orderShop);
        $manager->flush();

        foreach ($productsVetements as $product) {
            $orderDetails = new OrderDetails();
            $orderDetails->setProduct($product);
            $orderDetails->setQuantity(3);
            $orderDetails->setPrice(19.99);
            $orderDetails->setPriceTtc(60.85);
            $orderDetails->setOrderShop($orderShop);
            $manager->persist($orderDetails);
            $manager->flush();
        }

    }

}
