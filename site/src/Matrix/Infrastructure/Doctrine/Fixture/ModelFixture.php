<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Doctrine\Fixture;

use App\Matrix\Domain\Entity\Model;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ModelFixture extends Fixture
{
    public const REFERENCE_MODEL_CLASSIC = 'matrix_model_classic';

    public function load(ObjectManager $manager): void
    {
        $object = new Model('Classic');
        $this->setReference(self::REFERENCE_MODEL_CLASSIC, $object);
        $manager->persist($object);
        $manager->flush();
    }
}
