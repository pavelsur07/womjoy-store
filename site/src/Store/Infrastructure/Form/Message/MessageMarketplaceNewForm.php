<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Form\Message;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageMarketplaceNewForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                Type\TextType::class,
                [
                    'label' =>'Name',
                    'attr' => [
                        'placeholder' => 'Name',
                        'name' => 'firstName',
                    ],
                ]
            )
            ->add(
                'phone',
                Type\TextType::class,
                [
                    'label' => 'Phone',
                    'attr' => [
                        'placeholder' => '+7(999) 999-99-99',
                        'name' => 'phone',
                    ],
                ]
            )
            ->add(
                'email',
                Type\EmailType::class,
                [
                    'label' => 'E-mail',
                    'attr' => [
                        'placeholder' => 'exampel@app.com',
                        'name' => 'email',
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
