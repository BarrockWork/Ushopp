<?php

namespace App\Form;

use App\Entity\OrderShop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $status = [
            'STATUS.status0' => 0,
            'STATUS.status1' => 1,
            'STATUS.status2' => 2,
            'STATUS.status3' => 3,
            'STATUS.status4' => 4,
            'STATUS.status5' => 5
        ];

        $builder
            ->add('status', ChoiceType::class, [
                'choices' => $status,
                'multiple' => false,
                'expanded' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderShop::class,
        ]);
    }
}
