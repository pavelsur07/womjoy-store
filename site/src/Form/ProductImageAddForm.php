<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProductImageAddForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('files', Type\FileType::class, [
                'label' => 'Images (png, jpg file)',
                'mapped' => false,
                'multiple' => true,
                'attr'=>[
                    'accept' => 'image/*',
                ],
                'constraints' => [
                    new Assert\Count(['max' => 5]),
                    new Assert\All([
                        new Assert\Image(
                            [
                                'maxSize' => '10M',
                                'mimeTypes' => [
                                    'image/jpeg',
                                    'image/png',
                                ],
                                'mimeTypesMessage' => 'Please upload a valid Image document',
                            ]
                        ),
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
