<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control form-control-lg'],
                'label_attr' => ['class' => 'form-label'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a title',
                    ]),
                ]
            ])

            ->add('content', TextareaType::class, [
                'attr' => ['class' => 'form-control form-control-lg'],
                'label_attr' => ['class' => 'form-label'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a content',
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Your content should be at least {{ limit }} characters',
                    ]),
                ]
            ])

            ->add('image', UrlType::class, [
                'attr' => ['class' => 'form-control form-control-lg'],
                'label_attr' => ['class' => 'form-label'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a image',
                    ]),
                ]
            ])

            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true,
                'attr' => ['class' => 'form-control form-control-lg'],
                'label_attr' => ['class' => 'form-label'],
            ])

            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'expanded' => true,
                'multiple' => true,
                'attr' => ['class' => 'form-control form-control-lg'],
                'label_attr' => ['class' => 'form-label'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
