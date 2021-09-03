<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Filter\SearchProduct;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => [
                    'placeholder' => 'product.placeholder.category'
                ],
            ])
            ->add('price', MoneyType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'product.priceMax'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchProduct::class,
        ]);
    }
}
