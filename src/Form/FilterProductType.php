<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Filter\SearchProduct;
use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => ucfirst('name'),
                'attr' => [
                    'placeholder' => 'product.placeholder.category'
                ],
            ])
            ->add('maxPrice', MoneyType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'product.priceMax'
                ]
            ])
            ->add('minPrice', MoneyType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'product.priceMin'
                ]
            ])
        ->add('isBest', CheckboxType::class, [
            'label'    => 'product.isBest',
            'required' => false,
        ])
            ->add('sortProduct', ChoiceType::class, [
                'choices' => [
                    'product.orderBy' => null,
                    'product.moreRecent' => 'createdAt',
                    'product.older' => 'oldCreatedAt',
                    'product.ascendingPrice' => 'minPrice',
                    'product.decreasingPrice' => 'maxPrice'
                ],
                'label' => 'OrderBY',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchProduct::class,
        ]);
    }
}
