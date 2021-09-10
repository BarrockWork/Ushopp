<?php

namespace App\Form;

use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CarrierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                "attr" => [
                    "placeholder" => "carrier.form.nameCarrier",
                ],
            ])
            ->add('price', MoneyType::class, [
                "attr" => [
                    "placeholder" => "carrier.form.priceCarrier",
                ],
            ])
            ->add('description', TextareaType::class, [
                "attr" => [
                    "placeholder" => "carrier.form.descriptionCarrier",
                ],
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => true,
                'allow_delete' => true,
                'image_uri' => true,
                'asset_helper' => true,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Carrier::class,
        ]);
    }
}
