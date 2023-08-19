<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Doctrine\DataFixtures\Attribute;

use App\Store\Domain\Entity\Attribute\Attribute;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SizeFixture extends Fixture
{
    public const REFERENCE_ATTRIBUTE_SIZE = 'reference-size';

    public function load(ObjectManager $manager): void
    {
        $band = new Attribute(
            name: 'Size',
            type: Attribute::TYPE_SINGLE_CHOICE
        );

        $band->addVariant('XS');
        $band->addVariant('S');
        $band->addVariant('M');
        $band->addVariant('L');

        $this->setReference(self::REFERENCE_ATTRIBUTE_SIZE, $band);
        $manager->persist($band);
        $manager->flush();
    }
}
