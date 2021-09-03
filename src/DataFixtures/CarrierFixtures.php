<?php

namespace App\DataFixtures;

use App\Entity\Carrier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CarrierFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Generate 3 carrirs
        $this->generate3($manager);
        $manager->flush();
    }

    private function generate3(ObjectManager $manager) {
        $types = [
            'Colissimo' => 2.99,
            'Chronopost' => 4.99,
            'UPS' => 6.99
        ];

        foreach ($types as $name => $price) {
            $carrier = new Carrier();
            $carrier->setName($name);
            $carrier->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laboru");
            $carrier->setPrice($price);
            $carrier->setImageName('testImage');
            $carrier->setOriginalName('testImage');
            $carrier->setMimeType('jpg');
            $carrier->setImageSize(65);

            $manager->persist($carrier);
        }

        //Flush
        $manager->flush();

    }
}
