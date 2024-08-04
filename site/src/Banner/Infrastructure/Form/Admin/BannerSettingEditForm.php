<?php

declare(strict_types=1);

namespace App\Banner\Infrastructure\Form\Admin;

use App\Banner\Infrastructure\Repository\BannerRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BannerSettingEditForm extends AbstractType
{
    public function __construct(
        private readonly BannerRepository $menus,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'heroSlider',
                Type\ChoiceType::class,
                [
                    'choices' => $this->menus->getRootAll(),
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
