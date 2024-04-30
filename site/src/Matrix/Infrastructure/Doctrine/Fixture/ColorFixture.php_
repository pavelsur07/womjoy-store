<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Doctrine\Fixture;

use App\Matrix\Domain\Entity\Color;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ColorFixture extends Fixture
{
    public const REFERENCE_COLOR_BLACK = 'matrix_color_black';

    public function load(ObjectManager $manager): void
    {
        $object = new Color('Black');
        $manager->persist($object);
        $this->setReference(self::REFERENCE_COLOR_BLACK, $object);
        $manager->flush();
    }
}
