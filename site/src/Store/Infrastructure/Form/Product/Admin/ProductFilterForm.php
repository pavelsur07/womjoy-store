<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Form\Product\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFilterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Name',
                'onchange' => 'this.form.submit()',
            ]])
            ->add('article', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Article',
                'onchange' => 'this.form.submit()',
            ]]);
        /*            ->add(
                        'status',
                        Type\ChoiceType::class,
                        [
                            'choices' => array_combine(OrderStatus::list(), OrderStatus::list()),
                            'required' => false,
                            'placeholder' => 'All statuses',
                            'attr' => ['onchange' => 'this.form.submit()',
                                'translation_domain' => 'matrix_language',
                            ]]
                    );*/
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductFilter::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
