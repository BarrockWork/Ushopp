<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
            //Global informations
            FormField::addPanel('Informations générales'),
            TextField::new('name')->setLabel('Nom'),
            TextField::new('reference')->setLabel('Référence'),
            TextEditorField::new('description')->setLabel('Description'),
            AssociationField::new('category')->setLabel('Catégorie')->setRequired(true),
            BooleanField::new('isBest')
                ->setLabel('Produit phare ?')
                ->setHelp('Est-ce un produit phare ou non.'),
            BooleanField::new('active')
                ->setLabel('Activer ?')
                ->setHelp('Détermine si le produit sera visible sur le site'),
            //Delivery informations
            FormField::addPanel('Informations pour la livraison'),
            NumberField::new('weight')->setLabel('Poid (gramme)'),
            NumberField::new('size')->setLabel('Dimensions (LxWxH)'),
            //Price datas
            FormField::addPanel('Tarification'),
            MoneyField::new('price')->setCurrency("EUR")->setLabel('Prix'),
            IntegerField::new('tax')->setLabel('Taxe (%)'),
            IntegerField::new('ecotax')->setLabel('EcoTaxe (%)'),
            //Illustration
            FormField::addPanel('Ajouter une illustration')->onlyWhenCreating(),
            FormField::addPanel('Éditer l\'illustration')->onlyWhenUpdating(),
            ImageField::new('illustration')
                ->setLabel('Illustration (Format 64x64)')
                ->setHelp('Par soucis d\'optimisation, utilisez des images au format 64x64.')
                ->setBasePath('uploads/images/illustrations')
                ->setUploadDir("public/uploads/images/illustrations")
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false)//For the edition of the field

            //Todo create the images fields
//            FormField::addPanel('Ajouter des images')->onlyWhenCreating(),
//            FormField::addPanel('Éditer les images')->onlyWhenUpdating(),
//            CollectionField::new('images')->setLabel('Images')
//                ->allowAdd(true)
//                ->allowDelete(true)
//                ->setEntryType(FileType::class)
        ];
    }
}
