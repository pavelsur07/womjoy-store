<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Doctrine\DataFixtures\Attribute;

use App\Store\Domain\Entity\Attribute\Attribute;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BrandFixture extends Fixture
{
    public const REFERENCE_ATTRIBUTE_BRAND = 'reference-brand';

    public function load(ObjectManager $manager): void
    {
        $band = new Attribute(
            name: 'Brand',
            type: Attribute::TYPE_BRAND
        );
        $band->addVariant('Adidas');
        $band->addVariant('Nike');

        $this->setReference(self::REFERENCE_ATTRIBUTE_BRAND, $band);
        $manager->persist($band);
        $manager->flush();
    }
}
