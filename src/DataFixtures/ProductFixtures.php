<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductStock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Generate 5 figurines
        $this->generate5Figurines($manager);
        // Generate 5 clothes
        $this->generate5Vetements($manager);
        // Generate 5 posters
        $this->generate5Posters($manager);
        // Generate 5 accessoires
        $this->generate5Accessoires($manager);
        // Generate 5 lampes
        $this->generate5Lampes($manager);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }

    private function generate5Figurines(ObjectManager $manager){
        $category = $manager->getRepository(Category::class)->findOneByNameSlug('figurines');

        // 0: price, 1: tax, 2:ecoTax, 3:weight, 4:size, 5: active, 6: isBest
        $datas = [
            'Itachi Uchiwa' => [49.99, 10, 5, 0.350, '10x15x25', true, true],
            'Livaï Ackerman' => [59.99, 10, 5, 0.350, '10x15x25', true, true],
            'Roronoa Zoro' => [69.99, 10, 5, 0.350, '10x15x25', true, true],
            'Monkey D luffy' => [79.99, 10, 5, 0.350, '10x15x25', true, false],
            'Naruto Uzumaki' => [39.99, 10, 5, 0.350, '10x15x25', false, false],
        ];

        foreach ($datas as $name => $results) {

            $productStock = (new ProductStock())
                ->setQuantity(30)
            ;

            $product = new Product();
            $product->setProductStock($productStock);
            $product->setReference(uniqid('ref_product_'));
            $product->setCategory($category);
            $product->setName($name);
            $product->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laboru");
            $product->setDescriptionShort("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ");
            $product->setPrice($results[0]);
            $product->setTax($results[1]);
            $product->setEcotax($results[2]);
            $product->setWeight($results[3]);
            $product->setSize($results[4]);
            $product->setActive($results[5]);
            $product->setIsBest($results[6]);
            $product->setImageName('testImage');
            $product->setOriginalName('testImage');
            $product->setMimeType('jpg');
            $product->setImageSize(65);

            $manager->persist($product);
        }

        //Flush
        $manager->flush();
    }

    private function generate5Vetements(ObjectManager $manager){
        $category = $manager->getRepository(Category::class)->findOneByNameSlug('vetements');

        // 0: price, 1: tax, 2:ecoTax, 3:weight, 4:size, 5: active, 6: isBest
        $datas = [
            'Pull Itachi Uchiwa' => [79.99, 10, 5, 0.350, 'S', true, true],
            'Bermuda Livaï Ackerman' => [39.99, 10, 5, 0.350, 'M', true, true],
            'Chemise Roronoa Zoro' => [69.99, 10, 5, 0.350, 'XL', true, true],
            'Tee-shirt Monkey D luffy' => [79.99, 10, 5, 0.350, 'S', true, false],
            'Chaussettes Naruto Uzumaki' => [19.99, 10, 5, 0.350, '41', false, false],
        ];

        foreach ($datas as $name => $results) {

            $productStock = (new ProductStock())
                ->setQuantity(15)
            ;

            $product = new Product();
            $product->setProductStock($productStock);
            $product->setReference(uniqid('ref_product_'));
            $product->setCategory($category);
            $product->setName($name);
            $product->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laboru");
            $product->setDescriptionShort("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ");
            $product->setPrice($results[0]);
            $product->setTax($results[1]);
            $product->setEcotax($results[2]);
            $product->setWeight($results[3]);
            $product->setSize($results[4]);
            $product->setActive($results[5]);
            $product->setIsBest($results[6]);
            $product->setImageName('testImage');
            $product->setOriginalName('testImage');
            $product->setMimeType('jpg');
            $product->setImageSize(65);

            $manager->persist($product);
        }

        //Flush
        $manager->flush();
    }

    private function generate5Posters(ObjectManager $manager){
        $category = $manager->getRepository(Category::class)->findOneByNameSlug('posters');

        // 0: price, 1: tax, 2:ecoTax, 3:weight, 4:size, 5: active, 6: isBest
        $datas = [
            'Poster Itachi Uchiwa' => [99.99, 10, 5, 0.350, '10x15x25', true, true],
            'Poster Livaï Ackerman' => [99.99, 10, 5, 0.350, '10x15x25', true, true],
            'Poster Roronoa Zoro' => [199.99, 10, 5, 0.350, '10x15x25', true, true],
            'Poster Monkey D luffy' => [199.99, 10, 5, 0.350, '10x15x25', true, false],
            'Poster Naruto Uzumaki' => [99.99, 10, 5, 0.350, '10x15x25', false, false],
        ];

        foreach ($datas as $name => $results) {

            $productStock = (new ProductStock())
                ->setQuantity(30)
            ;

            $product = new Product();
            $product->setProductStock($productStock);
            $product->setReference(uniqid('ref_product_'));
            $product->setCategory($category);
            $product->setName($name);
            $product->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laboru");
            $product->setDescriptionShort("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ");
            $product->setPrice($results[0]);
            $product->setTax($results[1]);
            $product->setEcotax($results[2]);
            $product->setWeight($results[3]);
            $product->setSize($results[4]);
            $product->setActive($results[5]);
            $product->setIsBest($results[6]);
            $product->setImageName('testImage');
            $product->setOriginalName('testImage');
            $product->setMimeType('jpg');
            $product->setImageSize(65);

            $manager->persist($product);
        }

        //Flush
        $manager->flush();
    }

    private function generate5Accessoires(ObjectManager $manager){
        $category = $manager->getRepository(Category::class)->findOneByNameSlug('accessoires');

        // 0: price, 1: tax, 2:ecoTax, 3:weight, 4:size, 5: active, 6: isBest
        $datas = [
            'Bracelet Itachi Uchiwa' => [49.99, 10, 5, 0.350, 'unique', true, true],
            'Ceinture Livaï Ackerman' => [59.99, 10, 5, 0.350, '40', true, true],
            'Sabre Roronoa Zoro' => [69.99, 10, 5, 0.350, 'unique', true, true],
            'Chapeau Monkey D luffy' => [79.99, 10, 5, 0.350, 'unique', true, false],
            'Bandeau Naruto Uzumaki' => [39.99, 10, 5, 0.350, 'unique', false, false],
        ];

        foreach ($datas as $name => $results) {

            $productStock = (new ProductStock())
                ->setQuantity(30)
            ;

            $product = new Product();
            $product->setProductStock($productStock);
            $product->setReference(uniqid('ref_product_'));
            $product->setCategory($category);
            $product->setName($name);
            $product->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laboru");
            $product->setDescriptionShort("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ");
            $product->setPrice($results[0]);
            $product->setTax($results[1]);
            $product->setEcotax($results[2]);
            $product->setWeight($results[3]);
            $product->setSize($results[4]);
            $product->setActive($results[5]);
            $product->setIsBest($results[6]);
            $product->setImageName('testImage');
            $product->setOriginalName('testImage');
            $product->setMimeType('jpg');
            $product->setImageSize(65);

            $manager->persist($product);
        }

        //Flush
        $manager->flush();
    }

    private function generate5Lampes(ObjectManager $manager){
        $category = $manager->getRepository(Category::class)->findOneByNameSlug('lampes');

        // 0: price, 1: tax, 2:ecoTax, 3:weight, 4:size, 5: active, 6: isBest
        $datas = [
            'Lampes Itachi Uchiwa' => [49.99, 10, 5, 0.350, '10x15x25', true, true],
            'Lampes Livaï Ackerman' => [59.99, 10, 5, 0.350, '10x15x25', true, true],
            'Lampes Roronoa Zoro' => [69.99, 10, 5, 0.350, '10x15x25', true, true],
            'Lampes Monkey D luffy' => [79.99, 10, 5, 0.350, '10x15x25', true, false],
            'Lampes Naruto Uzumaki' => [39.99, 10, 5, 0.350, '10x15x25', false, false],
        ];

        foreach ($datas as $name => $results) {

            $productStock = (new ProductStock())
                ->setQuantity(30)
            ;

            $product = new Product();
            $product->setProductStock($productStock);
            $product->setReference(uniqid('ref_product_'));
            $product->setCategory($category);
            $product->setName($name);
            $product->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laboru");
            $product->setDescriptionShort("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ");
            $product->setPrice($results[0]);
            $product->setTax($results[1]);
            $product->setEcotax($results[2]);
            $product->setWeight($results[3]);
            $product->setSize($results[4]);
            $product->setActive($results[5]);
            $product->setIsBest($results[6]);
            $product->setImageName('testImage');
            $product->setOriginalName('testImage');
            $product->setMimeType('jpg');
            $product->setImageSize(65);

            $manager->persist($product);
        }

        //Flush
        $manager->flush();
    }

}
