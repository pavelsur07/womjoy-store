<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Form\Message;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageNewForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'topic',
                ChoiceType::class,
                [
                    'label' =>'Тема',
                    'choices'  => [
                        'Замена' =>'Substitution',
                        'Возврат' =>'return',
                        'Оплата' =>'pay',
                        'Информация о заказе' => 'Order information',
                        'Доставка' => 'Delivery',
                        'Информация об изделии' => 'Product information',
                        'Отзыв' => 'Feedback',
                        'Сотрудничество' => 'Collaboration',
                        'Другое' => 'Another',
                    ],
                ]
            )
            ->add(
                'name',
                Type\TextType::class,
                [
                    'label' =>'Имя',
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
                    'label' => 'Телефон',
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
            )
            ->add(
                'message',
                Type\TextareaType::class,
                [
                    'label' => 'Сообщение',
                    'attr' => [
                        'rows' => 8,
                        'placeholder' => 'Message',
                    ],
                ]
            )
            ->add(
                'isMyHideForm',
                Type\CheckboxType::class,
                [
                    'label' => 'Я соглашаюсь на обработку моих персональных данных.',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
