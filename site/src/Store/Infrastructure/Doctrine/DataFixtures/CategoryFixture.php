<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Doctrine\DataFixtures;

use App\Common\Infrastructure\Service\Slugify\SlugifyService;
use App\Store\Domain\Entity\Category\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    public const REFERENCE_WOMAN = 'reference-woman';
    public const REFERENCE_LEGGINGS = 'reference-leggings';
    public const REFERENCE_LEGGINGS_BLACK = 'reference-leggings-black';
    public const REFERENCE_PANTS = 'reference-pants';

    public function __construct(private readonly SlugifyService $slugify)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $levelRoot = new Category();
        $levelRoot->setName('Женжинам');
        $levelRoot->setSlug($this->slugify->generate(self::REFERENCE_WOMAN));
        $this->setReference(self::REFERENCE_WOMAN, $levelRoot);
        $manager->persist($levelRoot);
        $manager->flush();

        $levelOne = new Category();
        $levelOne->setName('Леггинсы');
        $levelOne->setParent($levelRoot);
        $levelOne->setSlug($this->slugify->generate(self::REFERENCE_LEGGINGS));
        $this->setReference(self::REFERENCE_LEGGINGS, $levelOne);
        $manager->persist($levelOne);

        $levelTwo = new Category();
        $levelTwo->setName('Леггинсы черные');
        $levelTwo->setParent($levelOne);
        $levelTwo->setSlug($this->slugify->generate(self::REFERENCE_LEGGINGS_BLACK));
        $this->setReference(self::REFERENCE_LEGGINGS_BLACK, $levelTwo);
        $manager->persist($levelTwo);

        $levelOne = new Category();
        $levelOne->setName('Брюки');
        $levelOne->setParent($levelRoot);
        $levelOne->setSlug($this->slugify->generate(self::REFERENCE_PANTS));
        $this->setReference(self::REFERENCE_PANTS, $levelOne);
        $manager->persist($levelOne);

        $manager->flush();
    }
}
