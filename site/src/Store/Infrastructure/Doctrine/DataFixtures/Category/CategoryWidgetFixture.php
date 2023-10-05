<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Doctrine\DataFixtures\Category;

use App\Common\Infrastructure\Service\Slugify\SlugifyService;
use App\Store\Domain\Entity\Category\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryWidgetFixture extends Fixture
{
    public const REFERENCE_NEW_ARRIVAL = 'new-arrivals';
    public const REFERENCE_POPULAR_PRODUCTS = 'popular-products';

    public function __construct(private readonly SlugifyService $slugify) {}

    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName('New arrivals');
        $category->setSlug($this->slugify->generate(self::REFERENCE_NEW_ARRIVAL));
        $this->setReference(self::REFERENCE_NEW_ARRIVAL, $category);

        $manager->persist($category);
        $manager->flush();

        $category = new Category();
        $category->setName($name = 'Popular products');
        $category->setParent($category);
        $category->setSlug($this->slugify->generate(self::REFERENCE_POPULAR_PRODUCTS));
        $category->setPrefixSlugProduct($this->slugify->generate($name));

        $this->setReference(self::REFERENCE_POPULAR_PRODUCTS, $category);
        $manager->persist($category);
        $manager->flush();
    }
}
