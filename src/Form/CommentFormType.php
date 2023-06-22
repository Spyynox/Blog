<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a title',
                    ]),
                ]
            ])

            ->add('content', TextareaType::class, [
                'attr' => ['class' => 'form-control', 'rows' => '6'],
                'label_attr' => ['class' => 'form-label'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a content',
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
