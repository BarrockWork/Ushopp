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
        $currentRoles = [];
        if(in_array('current_roles', $options)){
            $currentRoles = $options['current_roles'];
        }else{
            $currentRoles = ['ROLE_USER'];
        }

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
                'data' => $currentRoles
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
            'current_roles' => null
        ]);
    }
}
