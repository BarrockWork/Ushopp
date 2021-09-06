<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('global.notBlank'),
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => $this->translator->trans('user.firstname.minLength'),
                        'max' => 25,
                        'maxMessage' => $this->translator->trans('user.firstname.maxLength'),
                    ]),
                ]
            ])
            ->add('lastname', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('global.notBlank'),
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => $this->translator->trans('user.lastname.minLength'),
                        'max' => 25,
                        'maxMessage' => $this->translator->trans('user.lastname.maxLength'),
                    ]),
                ]
            ])
            ->add('subject', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('global.notBlank'),
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => $this->translator->trans('contact.errors.minMessage'),
                        'max' => 50,
                        'maxMessage' => $this->translator->trans('contact.errors.maxMessage'),
                    ]),
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('global.notBlank'),
                    ]),
//                    new Email([
//                        'invalid_message' => $this->translator->trans('contact.errors.validEmail')
//                    ]),
                ]
            ])
            ->add('content', TextareaType::class, [
                'required' => true,
                'help' => 'max 500',
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('global.notBlank'),
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => $this->translator->trans('contact.errors.minMessage'),
                        'max' => 500,
                        'maxMessage' => $this->translator->trans('contact.errors.maxMessage'),
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class
        ]);
    }
}
