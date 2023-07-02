<?php

declare(strict_types=1);

namespace App\Menu\Infrastructure\Form;

use App\Menu\Domain\Repository\MenuRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuSettingEditForm extends AbstractType
{
    public function __construct(
        private readonly MenuRepositoryInterface $menus,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'footerMenu',
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
