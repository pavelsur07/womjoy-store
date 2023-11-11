<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Form\AmoCRM;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AmoCRMoAccessTokenEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clientId', Type\TextType::class)
            ->add('baseDomain', Type\TextType::class)
            ->add('secret', Type\TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
