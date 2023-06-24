<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => ['class' => 'form-control form-control-lg'],
                'label_attr' => ['class' => 'form-label'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a username',
                    ]),
                ]
            ])

            ->add('firstname', TextType::class, [
                'attr' => ['class' => 'form-control form-control-lg'],
                'label_attr' => ['class' => 'form-label'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a firstname',
                    ]),
                ]
            ])

            ->add('lastname', TextType::class, [
                'attr' => ['class' => 'form-control form-control-lg'],
                'label_attr' => ['class' => 'form-label'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a lastname',
                    ]),
                ]
            ])

            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control form-control-lg'],
                'label_attr' => ['class' => 'form-label'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a email',
                    ]),
                    new Email([
                        'message' => 'The email {{ value }} is not a valid email.',
                    ]),
                ],
            ])

            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'form-control form-control-lg'],
                'label_attr' => ['class' => 'form-label'],
                'required' => false,
            ])

            ->add('logo', UrlType::class, [
                'attr' => ['class' => 'form-control form-control-lg'],
                'label_attr' => ['class' => 'form-label'],
                'required' => false,
            ])

            ->add('password', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control form-control-lg'],
                'label_attr' => ['class' => 'form-label'],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'max' => 255,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'maxMessage' => 'Your first name cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}