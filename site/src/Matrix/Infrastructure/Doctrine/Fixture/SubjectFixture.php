<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Doctrine\Fixture;

use App\Matrix\Domain\Entity\Subject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SubjectFixture extends Fixture
{
    public const REFERENCE_SUBJECT_LEGGINGS = 'matrix_subject_leggings';

    public function load(ObjectManager $manager): void
    {
        $subject = new Subject('Леггинсы');
        $this->setReference(self::REFERENCE_SUBJECT_LEGGINGS, $subject);
        $manager->persist($subject);
        $manager->flush();
    }
}
