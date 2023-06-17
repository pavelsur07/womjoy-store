<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Doctrine\DataFixtures;

use App\Store\Domain\Entity\Home\Home;
use App\Store\Domain\Entity\SeoMetadata;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HomeFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $home = new Home();
        $home->setSeoMetadata(
            new SeoMetadata(
                h1: 'H1 Default',
                title: 'Title Default',
                description: 'Description Default',
                isIndexOn: true
            )
        );
        $manager->persist($home);
        $manager->flush();
    }
}
