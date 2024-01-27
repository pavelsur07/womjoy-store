<?php

declare(strict_types=1);

namespace App\Setting\Infrastructure\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingEditBaseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('phone', Type\TelType::class)
            ->add('email', Type\EmailType::class)
            ->add('h1', Type\TextType::class, ['required'=>false])
            ->add('title', Type\TextType::class, ['required'=>false])
            ->add(
                'description',
                Type\TextareaType::class,
                [
                    'attr' => [
                        'rows' => 5,
                    ],
                    'required'=>false,
                ]
            )
            ->add('companyName', Type\TextType::class, ['required'=>false])
            ->add('storeName', Type\TextType::class, ['required'=>false])
            ->add('storeUrl', Type\TextType::class, ['required'=>false])
            ->add('postalCode', Type\TextType::class, ['required'=>false])
            ->add('addressCountry', Type\TextType::class, ['required'=>false])
            ->add('addressLocality', Type\TextType::class, ['required'=>false])
            ->add('streetAddress', Type\TextType::class, ['required'=>false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
