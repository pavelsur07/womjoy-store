<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Form\Home;

use App\Store\Domain\Repository\CategoryRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HomeAssignCategoryForm extends AbstractType
{
    public function __construct(private readonly CategoryRepositoryInterface $categories) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'category',
                Type\ChoiceType::class,
                [
                    'choices' => $this->categories->getCategoryTree(),
                    'choice_label' => 'label',
                    'choice_value' => 'value',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
