<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Form\Product;

use App\Matrix\Domain\Entity\Color;
use App\Matrix\Domain\Entity\Model;
use App\Matrix\Domain\Entity\Subject;
use App\Matrix\Domain\Entity\ValueObject\ProductStatus;
use App\Matrix\Infrastructure\Repository\Product\ProductFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFilterListForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Name',
                'onchange' => 'this.form.submit()',
            ]])
            ->add('article', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Article',
                'onchange' => 'this.form.submit()',
            ]])
            ->add(
                'status',
                Type\ChoiceType::class,
                [
                    'choices' => array_combine(ProductStatus::list(), ProductStatus::list()),
                    'required' => false,
                    'placeholder' => 'All statuses',
                    'attr' => ['onchange' => 'this.form.submit()',
                        'translation_domain' => 'matrix_language',
                    ]]
            )
            ->add(
                'subject',
                EntityType::class,
                [
                    'class' => Subject::class,
                    'placeholder' => 'All subjects',
                    'required' => false,
                    'choice_label' => 'name',
                    'attr' => [
                        'onchange' => 'this.form.submit()',
                    ],
                ]
            )
            ->add(
                'model',
                EntityType::class,
                [
                    'class' => Model::class,
                    'placeholder' => 'All models',
                    'required' => false,
                    'choice_label' => 'name',
                    'attr' => ['onchange' => 'this.form.submit()',
                    ]]
            )

            ->add(
                'color',
                EntityType::class,
                [
                    'class' => Color::class,
                    'placeholder' => 'All colors',
                    'required' => false,
                    'choice_label' => 'name',
                    'attr' => ['onchange' => 'this.form.submit()',
                    ]]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductFilter::class,
        ]);
    }
}
