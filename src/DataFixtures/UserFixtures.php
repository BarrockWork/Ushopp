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
        // Create an user with per role
        $this->createRole($manager);

    }

    private function createRole(ObjectManager $manager) {
        // ROLE_USER
        $user = new User();
        $user->setEmail('user@admin.fr');
        $user->setFirstName('UserName');
        $user->setLastName('User');
        $user->setPhoneNumber('0666666666');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user,'admin1234'));
        $manager->persist($user);

        // ROLE_ADMIN
        $admin = new User();
        $admin->setEmail('admin@admin.fr');
        $admin->setFirstName('Admin');
        $admin->setLastName('Local');
        $admin->setPhoneNumber('0666666666');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin,'admin1234'));
        $manager->persist($admin);

        // ROLE_SUPER_ADMIN
        $super_admin = new User();
        $super_admin->setEmail('superadmin@admin.fr');
        $super_admin->setFirstName('Super');
        $super_admin->setLastName('Admin');
        $super_admin->setPhoneNumber('0666666666');
        $super_admin->setRoles(['ROLE_SUPER_ADMIN']);
        $super_admin->setPassword($this->passwordHasher->hashPassword($super_admin,'admin1234'));
        $manager->persist($super_admin);

        // ROLE_PREMIUM
        $premium = new User();
        $premium->setEmail('premium@admin.fr');
        $premium->setFirstName('Premium');
        $premium->setLastName('User');
        $premium->setPhoneNumber('0666666666');
        $premium->setRoles(['ROLE_PREMIUM']);
        $premium->setPassword($this->passwordHasher->hashPassword($premium,'admin1234'));
        $manager->persist($premium);

        // Flush
        $manager->flush();
    }
}
