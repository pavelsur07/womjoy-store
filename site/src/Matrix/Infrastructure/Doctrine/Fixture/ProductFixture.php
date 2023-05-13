<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Doctrine\Fixture;

use App\Matrix\Domain\Entity\Product\Product;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $subject = $this->getReference(SubjectFixture::REFERENCE_SUBJECT_LEGGINGS);
        $color = $this->getReference(ColorFixture::REFERENCE_COLOR_BLACK);
        $model = $this->getReference(ModelFixture::REFERENCE_MODEL_CLASSIC);

        foreach ($this->listProducts() as $product) {
            $object = new Product(
                createdAt: new DateTimeImmutable(),
                article: $product['name'],
                name: $product['article'],
                subject: $subject,
                model: $model,
                color: $color
            );
            $manager->persist($object);
        }

        $manager->flush();
    }

    public function listProducts(): array
    {
        return [
            [
                'name' => 'Леггинсы Sexy',
                'article' => 'ART-000001',
            ],
            [
                'name' => 'Леггинсы Classic',
                'article' => 'ART-000002',
            ],
            [
                'name' => 'Леггинсы Energy',
                'article' => 'ART-000003',
            ],
            [
                'name' => 'Леггинсы Base',
                'article' => 'ART-000004',
            ],
        ];
    }

    public function getDependencies(): array
    {
        return [
            SubjectFixture::class,
            ModelFixture::class,
            ColorFixture::class,
        ];
    }
}
