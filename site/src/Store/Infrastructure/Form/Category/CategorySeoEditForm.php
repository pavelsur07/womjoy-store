<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Form\Category;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorySeoEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('h1', Type\TextType::class, ['required' => false])
            ->add('seoTitle', Type\TextType::class, ['required' => false])
            ->add('seoDescription', Type\TextType::class, ['required' => false])
            ->add('slug', Type\TextType::class)
            ->add('prefixSlugProduct', Type\TextType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
