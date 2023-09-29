<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Form\Product;

use App\Matrix\Domain\Entity\Color;
use App\Matrix\Domain\Entity\Model;
use App\Matrix\Domain\Entity\Subject;
use App\Matrix\Domain\Entity\ValueObject\ProductStatus;
use App\Matrix\Domain\Repository\Product\ProductRepositoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductEditForm extends AbstractType
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', Type\TextType::class)
            ->add('article', Type\TextType::class)
            ->add('path', Type\TextType::class, ['required' =>false])
            ->add(
                'subject',
                EntityType::class,
                [
                    'class' => Subject::class,
                    'choice_label' => 'name',
                ]
            )
            ->add(
                'model',
                EntityType::class,
                [
                    'class' => Model::class,
                    'choice_label' => 'name',
                ]
            )
            ->add(
                'color',
                EntityType::class,
                [
                    'class' => Color::class,
                    'choice_label' => 'name',
                ]
            )
            ->add('status', Type\ChoiceType::class, [
                'choices' => array_combine(ProductStatus::list(), ProductStatus::list()),
                // 'required' => false,
                // 'expanded' => true,
                'translation_domain' => 'matrix_language',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
