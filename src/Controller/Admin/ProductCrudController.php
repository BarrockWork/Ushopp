<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Liste des produits')
            ->setPageTitle('index', '%entity_label_plural%')
            ->setHelp('index', 'Gérer la liste des produits')
            ->setPageTitle('new', 'Créer un produit')
            ->setHelp('new', 'Ajouter un nouveau produit')
            ->setPageTitle('detail', 'Profil utilisateur')
            ->setHelp('detail', 'Consulter les informations du produit')
            ->setPageTitle('edit', 'Éditer un produit')
            ->setHelp('edit', 'Éditer les informations d\'un produit')
            ->setSearchFields(['name', 'reference', 'price', 'weight', 'size', 'active'])
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Informations générales'),
            TextField::new('name')->setLabel('Nom'),
            TextField::new('reference')->setLabel('Référence'),
            TextEditorField::new('description')->setLabel('Description'),
            AssociationField::new('category')->setLabel('Catégorie'),
            BooleanField::new('active')->setLabel('Activer ?'),
            FormField::addPanel('Informations pour la livraison'),
            NumberField::new('weight')->setLabel('Poid'),
            NumberField::new('size')->setLabel('Dimensions (LxWxH)'),
            FormField::addPanel('Tarification'),
            MoneyField::new('price')->setCurrency("EUR")->setLabel('Prix'),
            PercentField::new('tax')->setLabel('Taxe'),
            PercentField::new('ecotax')->setLabel('EcoTaxe'),
            FormField::addPanel('Ajouter des images'),
//            CollectionField::new('images')->setLabel('Images')
//                ->allowAdd(true)
//                ->allowDelete(true)
//            ->setEntryType(ImageType::class)
//            ImageField::new('images')
//                ->setBasePath('uploads/images/products')
//                ->setUploadDir("public/uploads/images/products")
//                ->setUploadedFileNamePattern('[randomhash].[extension]')
//                ->setRequired(false),
        ];
    }
}
