<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\OrderShop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('createdAt')
            ->add('status')
            ->add('paymentAt')
            ->add('deliveryAddress')
            ->add('carrier_name')
            ->add('carrier_price')
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderShop::class,
        ]);
    }
}
