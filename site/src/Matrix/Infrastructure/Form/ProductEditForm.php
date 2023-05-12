<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Form;

use App\Matrix\Domain\Entity\ValueObject\ProductStatus;
use App\Matrix\Domain\Repository\ProductRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductEditForm extends AbstractType
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', Type\TextType::class)
/*            ->add('status', Type\ChoiceType::class, ['choices' => [
                'Draft' => ProductStatus::DRAFT,
                'Development' => ProductStatus::DEVELOPMENT,
                'Ready development' => ProductStatus::READY_DEVELOPMENT,
                'Wait sale' => ProductStatus::WAIT_SALE,
                'Ready sale' => ProductStatus::READY_SALE,
                'Sale' => ProductStatus::SALE,
                'Archived' => ProductStatus::ARCHIVED,
            ], ])*/
            ->add('status', Type\ChoiceType::class, [
                'choices' => array_combine(ProductStatus::list(), ProductStatus::list()),
                //'required' => false,
                //'expanded' => true,
                'translation_domain' => 'matrix_language',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
