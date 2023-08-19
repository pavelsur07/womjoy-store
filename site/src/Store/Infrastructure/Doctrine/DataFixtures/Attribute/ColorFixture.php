<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Doctrine\DataFixtures\Attribute;

use App\Store\Domain\Entity\Attribute\Attribute;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ColorFixture extends Fixture
{
    public const REFERENCE_ATTRIBUTE_COLOR = 'reference-color';

    public function load(ObjectManager $manager): void
    {
        $band = new Attribute(
            name: 'Color',
            type: Attribute::TYPE_COLOR
        );
        $band->addVariant('Black');
        $band->addVariant('White');
        $band->addVariant('Green');

        $this->setReference(self::REFERENCE_ATTRIBUTE_COLOR, $band);
        $manager->persist($band);
        $manager->flush();
    }
}
