<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        // Crate a new admin
        $this->createAdmin($manager);

    }

    private function createAdmin(ObjectManager $manager) {
        $admin = new User();
        $admin->setEmail('admin@admin.fr');
        $admin->setFirstName('Admin');
        $admin->setLastName('Local');
        $admin->setPhoneNumber('0666666666');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setImageName('testImage');
        $admin->setOriginalName('testImage');
        $admin->setMimeType('jpg');
        $admin->setImageSize(65);
        $admin->setPassword($this->passwordHasher->hashPassword($admin,'admin1234'));
        $manager->persist($admin);
        $manager->flush();
    }
}
