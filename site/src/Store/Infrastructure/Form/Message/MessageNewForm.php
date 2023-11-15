<?php

namespace App\Store\Infrastructure\Form\Message;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

class MessageNewForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class,
                [
                'choices'  => [
                    'Возврат' =>'return',
                    'Оплата' =>'pay',
                    'Maybe' => null,
                    'Yes' => true,
                    'No' => false,
                    ],
                ]
            )
            /*->add('typeMessage', Type\ChoiceType::class,
                [
                    'choices' =>
                        [
                            'Возврат' =>'return',
                            'Оплата' =>'pay',
                            'infoOrder' => 'Информация о заказе',
                            'infoProduct' => 'Информация о продукте',
                            'review' => 'Отзыв',
                            'cooperation' => 'Сотрудничество',
                            'other' => 'Другое',
                        ],
                    'choice_label' => 'label',
                    'choice_value' => 'value',
                ])*/
            ->add('name', Type\TextType::class)
            ->add('phone', Type\TextType::class)
            ->add('email', Type\EmailType::class)
            ->add(
                'message',
                Type\TextareaType::class,
                [
                    'attr' => ['rows' => 8],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}