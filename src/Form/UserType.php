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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = array(
            'Utilisateur' => 'ROLE_USER',
            'Premium' => 'ROLE_PREMIUM',
            'Admin' => 'ROLE_ADMIN',
            'Super-Admin' => 'ROLE_SUPER_ADMIN'
        );

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
            ->add('roles', ChoiceType::class, [
                'choices' => $roles,
                'multiple' => true,
                'expanded' => false,
                'label' => 'Jour(s) *',
                'data' => array_keys($roles)
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
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'delete',
                'download_label' => 'download',
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
