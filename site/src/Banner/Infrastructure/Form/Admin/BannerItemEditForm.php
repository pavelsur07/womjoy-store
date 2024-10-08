<?php

declare(strict_types=1);

namespace App\Banner\Infrastructure\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BannerItemEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', Type\TextType::class, ['required' => false])
            ->add('description', Type\TextType::class, ['required' => false])
            ->add('url', Type\TextType::class, ['required' => false])
            ->add('isShortBanner', Type\CheckboxType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
