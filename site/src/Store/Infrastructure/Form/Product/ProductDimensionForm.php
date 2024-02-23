<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Form\Product;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductDimensionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'length',
                Type\IntegerType::class,
                [
                    'label' =>'Length cm',
                ]
            )
            ->add('width', Type\IntegerType::class, [
                'label' =>'Width cm',
            ])
            ->add('height', Type\IntegerType::class, [
                'label' =>'Height cm',
            ])
            ->add('weight', Type\IntegerType::class, [
                'label' =>'Weight g.',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
