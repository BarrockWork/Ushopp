<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Liste des utlilisateurs')
            ->setPageTitle('index', '%entity_label_plural%')
            ->setHelp('index', 'Gérer la liste des utilisateurs')
            ->setPageTitle('new', 'Créer un utilisateur')
            ->setHelp('new', 'Ajouter un nouvel utilisateur')
            ->setPageTitle('detail', 'Profil utilisateur')
            ->setHelp('detail', 'Consulter les informations de l\'utilisateur')
            ->setPageTitle('edit', 'Éditer un utilisateur')
            ->setHelp('edit', 'Éditer les information d\'un utilisateur')
            ->setSearchFields(['email', 'firstName', 'lastName', 'phoneNumber'])

            ;
    }

    public function configureFields(string $pageName): iterable
    {
        $roles = [
            'Utilisateur' => 'ROLE_USER',
            'Administrateur'=> 'ROLE_ADMIN'
        ];

        return [
            FormField::addPanel('Informations de connexion'),
            EmailField::new('email')->setLabel('Email'),
            TextField::new('password')->setLabel('Mot de passe')->onlyWhenCreating(),
            FormField::addPanel('Informations générales'),
            TextField::new('lastname')->setLabel('Nom'),
            TextField::new('firstname')->setLabel('Prénom'),
            TelephoneField::new('phoneNumber')->setLabel('Téléphone'),
            FormField::addPanel('Configurer le(s) rôle(s)'),
            ChoiceField::new('roles')->setLabel('Rôle(s)')->setChoices($roles)->allowMultipleChoices(true),
        ];
    }

}
