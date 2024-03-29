<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Form\Attribute;

use App\Store\Domain\Entity\Attribute\Attribute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', Type\TextType::class)
            ->add('type', Type\ChoiceType::class, ['choices' => [
                'Single choice' => Attribute::TYPE_SINGLE_CHOICE,
                'Multi choice' => Attribute::TYPE_MULTI_CHOICE,
                'Brand/Manufacture' =>Attribute::TYPE_BRAND,
                'Color' => Attribute::TYPE_COLOR,
            ]])
            ->add('isVisibleFilter', Type\CheckboxType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
