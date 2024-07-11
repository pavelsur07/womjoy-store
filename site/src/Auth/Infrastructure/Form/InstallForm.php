<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstallForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'firstName',
                Type\TextType::class,
                [
                    'label' => 'ИМЯ *',
                ]
            )
            ->add(
                'phone',
                Type\TextType::class,
                [
                    'label'=>'МОБИЛЬНЫЙ ТЕЛЕФОН * ',
                ]
            )
            ->add(
                'email',
                Type\EmailType::class,
                [
                    'label'=>'ЭЛЕКТРОННАЯ ПОЧТА * ',
                ]
            )
            ->add(
                'password',
                Type\PasswordType::class,
                [
                    'label'=>'ПАРОЛЬ * ',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
