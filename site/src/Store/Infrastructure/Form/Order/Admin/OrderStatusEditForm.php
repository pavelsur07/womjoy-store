<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Form\Order\Admin;

use App\Store\Domain\Entity\Order\ValueObject\OrderStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderStatusEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'status',
                Type\ChoiceType::class,
                [
                    'choices' => array_combine(OrderStatus::list(), OrderStatus::list()),
                    'required' => false,
                    'placeholder' => 'All statuses',
                    'attr' => [
                        'translation_domain' => 'matrix_language',
                    ]]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
