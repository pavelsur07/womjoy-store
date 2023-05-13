<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Form\Product;

use App\Matrix\Domain\Entity\Color;
use App\Matrix\Domain\Entity\Model;
use App\Matrix\Domain\Entity\Subject;
use App\Matrix\Domain\Repository\Product\ProductRepositoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductCreatedForm extends AbstractType
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('article')
            ->add('name')
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
            ->add(
                'createdAt',
                Type\DateType::class,
                [
                    'widget' => 'single_text',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
