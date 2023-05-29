<?php

declare(strict_types=1);

namespace App\Guarantee\Infrastructure\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuaranteeNewForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message', Type\TextareaType::class)
            ->add('files', Type\FileType::class)
            ->add(
                'email',
                Type\EmailType::class,
                [
                    'attr' => [
                        'placeholder' => 'email@example.com',
                    ],
                ]
            )
            ->add(
                'phone',
                Type\TextType::class,
                [
                    'attr' => [
                        'placeholder' => '+7(999)999-99-99',
                    ],
                ]
            )
            ->add(
                'isConfirm',
                Type\CheckboxType::class,
                [
                    'required' => true,
                    'label_attr' => ['hidden'=> true],
                ]
            )
            ->add(
                'isSubscribe',
                Type\CheckboxType::class,
                [
                    'required' => true,
                    'label_attr' => ['hidden'=> true],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
