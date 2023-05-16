<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Form\Product;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', Type\TextType::class)
            ->add(
                'description',
                Type\TextareaType::class,
                [
                    'attr' => ['rows' => 8],
                ]
            )
            ->add('price', Type\IntegerType::class)
            ->add('newPrice', Type\IntegerType::class, ['required' => false])
            ->add('seoTitle', Type\TextType::class, ['required' => false])
            ->add('seoDescription', Type\TextType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
