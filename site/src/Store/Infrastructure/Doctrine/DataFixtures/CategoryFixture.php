<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Doctrine\DataFixtures;

use App\Store\Domain\Entity\Category\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    public const REFERENCE_WOMAN = 'reference_woman';

    public function load(ObjectManager $manager): void
    {
        $levelRoot = new Category();
        $levelRoot->setName('Женжинам');
        $this->setReference(self::REFERENCE_WOMAN, $levelRoot);
        $manager->persist($levelRoot);
        $manager->flush();
    }
}
