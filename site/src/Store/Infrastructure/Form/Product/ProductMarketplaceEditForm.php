<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Form\Product;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductMarketplaceEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('wildberriesUrl', Type\TextType::class, ['required' => false])
            ->add('ozonUrl', Type\TextType::class, ['required' => false])
            ->add('yandexUrl', Type\TextType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
