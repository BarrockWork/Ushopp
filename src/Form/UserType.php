<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                "attr" => [
                    "placeholder" => "user.form.firstname",
                ],
            ])
            ->add('lastName', TextType::class, [
                "attr" => [
                    "placeholder" => "user.form.lastname",
                ],
            ])
            ->add('email', EmailType::class, [
                "attr" => [
                    "placeholder" => "user.form.email",
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                "attr" => [
                    "placeholder" => "user.form.password",
                ],
            ])
            ->add('phoneNumber', TelType::class, [
                "attr" => [
                    "placeholder" => "user.form.phoneNumber",
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
