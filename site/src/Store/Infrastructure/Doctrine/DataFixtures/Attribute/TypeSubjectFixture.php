<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Doctrine\DataFixtures\Attribute;

use App\Store\Domain\Entity\Attribute\Attribute;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeSubjectFixture extends Fixture
{
    public const REFERENCE_ATTRIBUTE_TYPE_SUBJECT = 'reference-type-subject';

    public function load(ObjectManager $manager): void
    {
        $band = new Attribute(
            name: 'Type Subject',
            type: Attribute::TYPE_MULTI_CHOICE
        );

        $band->addVariant('Subject type 1');
        $band->addVariant('Subject type 2');
        $band->addVariant('Subject type 3');
        $band->setIsVisibleFilter(true);

        $this->setReference(self::REFERENCE_ATTRIBUTE_TYPE_SUBJECT, $band);
        $manager->persist($band);
        $manager->flush();
    }
}
