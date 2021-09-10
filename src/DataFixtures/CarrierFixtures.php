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
            'Colissimo' => 4.99,
            'Chronopost' => 6.99,
            'UPS' => 14.99
        ];

        foreach ($types as $name => $price) {
            $carrier = new Carrier();
            $carrier->setName($name);
            $carrier->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.");
            $carrier->setPrice($price);
            $carrier->setImageName($name.'.png');
            $carrier->setOriginalName($name);
            $carrier->setMimeType('image/png');
            $carrier->setImageSize(65);

            $manager->persist($carrier);
        }

        //Flush
        $manager->flush();

    }
}
