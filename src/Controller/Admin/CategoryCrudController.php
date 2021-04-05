<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Catégorie')
            ->setEntityLabelInPlural('Liste des catégories de produits')
            ->setPageTitle('index', '%entity_label_plural%')
            ->setHelp('index', 'Gérer les catégories des produits')
            ->setPageTitle('new', 'Créer une catégorie')
            ->setHelp('new', 'Ajouter une nouvelle catégorie')
            ->setPageTitle('detail', 'Infos de la catégorie')
            ->setHelp('detail', 'Consulter les informations de la catégorie')
            ->setPageTitle('edit', 'Éditer une catégorie')
            ->setHelp('edit', 'Éditer les information d\'une catégorie')
            ->setSearchFields(['name','description', 'active'])
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Nom'),
            TextEditorField::new('description')->setLabel('Description'),
            BooleanField::new('active')->setLabel('Activer ?')
        ];
    }

}
