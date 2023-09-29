<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Form\Home;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HomeEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('h1', Type\TextType::class)
            ->add('title', Type\TextType::class)
            ->add('description', Type\TextType::class)
            ->add('isActiveSeoText', Type\CheckboxType::class, ['required' => false])
            ->add(
                'seoText',
                Type\TextareaType::class,
                [
                    'attr' => ['rows' => 8],
                ]
            )
            ->add('isActiveBestseller', Type\CheckboxType::class, ['required' => false])
            ->add('hrefBestseller', Type\TextType::class)
            ->add('isActiveNewProduct', Type\CheckboxType::class, ['required' => false])
            ->add('hrefNewProduct', Type\TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
