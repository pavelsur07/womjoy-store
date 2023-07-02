<?php

declare(strict_types=1);

namespace App\Page\Infrastructure\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageSeoEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('h1', Type\TextType::class)
            ->add('title', Type\TextType::class)
            ->add(
                'description',
                Type\TextareaType::class,
                [
                    'attr' => [
                        'rows' => 5,
                    ],
                ]
            )
            ->add('slug', Type\TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
