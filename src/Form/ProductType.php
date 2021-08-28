<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'product.placeholder.name'
                ],
                'required' => true
            ])
//            ->add('reference', TextType::class, [
//                'attr' => [
//                    'placeholder' => 'product.placeholder.reference'
//                ],
//                'required' => true
//            ])
            ->add('price', NumberType::class, [
                'attr' => [
                    'placeholder' => 'product.placeholder.price'
                ],
                'required' => true
            ])
            ->add('tax',NumberType::class, [
                'attr' => [
                    'placeholder' => 'product.placeholder.tax'
                ],
                'required' => true
            ])
            ->add('ecotax', NumberType::class, [
                'attr' => [
                    'placeholder' => 'product.placeholder.ecotax'
                ],
                'required' => false
            ])
            ->add('descriptionShort', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'product.descriptionShort'
                ],
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'product.placeholder.description'
                ],
                'required' => true
            ])
            ->add('weight', NumberType::class, [
                'attr' => [
                    'placeholder' => 'product.placeholder.weight'
                ],
                'required' => true
            ])
            ->add('size', TextType::class, [
                'attr' => [
                    'placeholder' => 'product.placeholder.size'
                ],
                'required' => true
            ])
            ->add('active', ChoiceType::class, [
                'choices' => [
                  'yes' => true,
                  'no' => false
                ],
                'required' => true
            ])
            ->add('isBest', ChoiceType::class, [
                'choices' => [
                    'yes' => true,
                    'no' => false
                ],
                'required' => true
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => [
                    'placeholder' => 'product.placeholder.category'
                ],
            ])
            ->add('stock',IntegerType::class, [
                'attr' => [
                    'placeholder' => 'product.placeholder.stock'
                ],
                'required' => true
            ])
            ->add('imageFile', VichImageType::class, [
                'help' => 'jpeg, png',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'delete',
                'download_label' => 'download',
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
            ])
            ->add('productImages', CollectionType::class, [
                'entry_type' => ProductImagesType::class,
                'entry_options' => array('label'=>false),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) {
                // Generate an unique reference
                $product = $event->getData();
                $product->setReference(uniqid('ref_product_'));
                $event->setData($product);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
