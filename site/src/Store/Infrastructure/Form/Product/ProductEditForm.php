<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Form\Product;

use App\Store\Domain\Repository\CategoryRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductEditForm extends AbstractType
{
    public function __construct(private readonly CategoryRepositoryInterface $categories)
    {
    }

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
            ->add(
                'mainCategory',
                Type\ChoiceType::class,
                [
                    // 'choices' => array_combine($this->categories->getCategoryTree(), $this->categories->getCategoryTree()),
                    'choices' => $this->categories->getCategoryTree(),
                    'choice_label' => 'label',
                    'choice_value' => 'value',
                ]
            )
            ->add('price', Type\IntegerType::class)
            ->add('listPrice', Type\IntegerType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
