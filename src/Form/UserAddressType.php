<?php

namespace App\Form;

use App\Entity\UserAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder
            ->add('name', TextType::class, [
                'required' => true
            ])
            ->add('company', TextType::class, [
                'required' => false
            ])
            ->add('phoneNumber', TelType::class, [
                'data' => $user->getPhoneNumber(),
                'required' => true
            ])
            ->add('city', TextType::class, [
                'required' => true
            ])
            ->add('address', TextareaType::class, [
                'required' => true
            ])
            ->add('zipCode',IntegerType::class, [
                'required' => true
            ])
            ->add('country', CountryType::class, [
                'required' => true
            ])
            ->add('firstName', TextType::class, [
                'data' => $user->getFirstname(),
                'required' => true
            ])
            ->add('lastName', TextType::class, [
                'data' => $user->getLastname(),
                'required' => true
            ])
            ->add('others', TextareaType::class, [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserAddress::class,
            'user' => null
        ]);
    }
}
