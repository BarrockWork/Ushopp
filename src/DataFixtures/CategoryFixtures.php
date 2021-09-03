<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Generate 5 categories
        $this->generate5($manager);
        $manager->flush();
    }

    private function generate5(ObjectManager $manager) {
        $types = ['Vêtements', 'posters', 'accessoires', 'figurines', 'lampes'];

        foreach ($types as $typeCat) {
            $category = new Category();
            $category->setName($typeCat);
            $category->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laboru");
            $category->setImageName('testImage');
            $category->setOriginalName('testImage');
            $category->setMimeType('jpg');
            $category->setImageSize(65);

            // Disable lampes
            if(strcmp($typeCat, 'lampes') === 0) {
                $category->setActive(false);
            }
            $manager->persist($category);
        }
        $manager->flush();

    }
}
