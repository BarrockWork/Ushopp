<?php

namespace App\Form;

use App\Entity\Carrier;
use App\Entity\UserAddress;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderFrontType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder
            ->add('addresses', EntityType::class, [
                'label' => 'shop.order.address.choose',
                'required' => true,
                'class' => UserAddress::class,
                'choices' => $user->getUserAddress(),
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'class' => "orderAddresses"
                ]
            ])
            ->add('carriers', EntityType::class, [
                'label' => 'shop.order.carrier.choose',
                'required' => true,
                'class' => Carrier::class,
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'class' => "orderCarriers"
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'shop.order.validation.title',
                'attr' => [
                    'class' => 'btn btn-success btn-block',
                    'title' => 'shop.order.validation.title'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'user' => []
        ]);
    }
}
