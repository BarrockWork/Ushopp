<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateAdminCommand extends Command
{
    protected static $defaultName = 'create-admin';
    protected static $defaultDescription = 'Add a short description for your command';
    private $em;
    private $passwordHasher;
    public function __construct(string $name = null, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct($name);
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Créer un admin et un super admin.')
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // ROLE_ADMIN
        $admin = new User();
        $admin->setEmail('admin@admin.fr');
        $admin->setFirstName('Admin');
        $admin->setLastName('Local');
        $admin->setPhoneNumber('0666666666');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin,'admin1234%'));
        $this->em->persist($admin);

        // ROLE_SUPER_ADMIN
        $super_admin = new User();
        $super_admin->setEmail('superadmin@admin.fr');
        $super_admin->setFirstName('Super');
        $super_admin->setLastName('Admin');
        $super_admin->setPhoneNumber('0666666666');
        $super_admin->setRoles(['ROLE_SUPER_ADMIN']);
        $super_admin->setPassword($this->passwordHasher->hashPassword($super_admin,'admin1234%'));
        $this->em->persist($super_admin);

        $this->em->flush();


        $io->success('Les utilisateurs admin et superAdmin ont été créés');

        return Command::SUCCESS;
    }
}
