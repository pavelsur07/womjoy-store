<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Doctrine\DataFixtures\Category;

use App\Common\Infrastructure\Service\Slugify\SlugifyService;
use App\Store\Domain\Entity\Category\Category;
use App\Store\Infrastructure\Doctrine\DataFixtures\Attribute\BrandFixture;
use App\Store\Infrastructure\Doctrine\DataFixtures\Attribute\ColorFixture;
use App\Store\Infrastructure\Doctrine\DataFixtures\Attribute\SizeFixture;
use App\Store\Infrastructure\Doctrine\DataFixtures\Attribute\TypeSubjectFixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE_WOMAN = 'reference-woman';
    public const REFERENCE_LEGGINGS = 'reference-leggings';
    public const REFERENCE_LEGGINGS_BLACK = 'reference-leggings-black';
    public const REFERENCE_PANTS = 'reference-pants';

    public function __construct(private readonly SlugifyService $slugify) {}

    public function load(ObjectManager $manager): void
    {
        $levelRoot = new Category();
        $levelRoot->setName('Женжинам');
        $levelRoot->setSlug($this->slugify->generate(self::REFERENCE_WOMAN));
        $this->setReference(self::REFERENCE_WOMAN, $levelRoot);
        $manager->persist($levelRoot);
        $manager->flush();

        $levelOne = new Category();
        $levelOne->setName($name = 'Леггинсы');
        $levelOne->setParent($levelRoot);
        $levelOne->setSlug($this->slugify->generate(self::REFERENCE_LEGGINGS));
        $levelOne->setPrefixSlugProduct($this->slugify->generate($name));
        $levelOne->setTitleProductTemplate('[Name] купить по цене [price] руб. в интернет-магазине Womjoy art - [article]');
        $levelOne->setDescriptionProductTemplate('[name] артикул - [article] купить в интернет-магазине womjoy.ru. Быстрая доставка, подарочная упаковка. Выгодные цены, скидки и акции! Заказ можно сделать на сайте или по телефону [phoneNumber]');

        $this->setReference(self::REFERENCE_LEGGINGS, $levelOne);

        $levelOne->assignAttribute($this->getReference(SizeFixture::REFERENCE_ATTRIBUTE_SIZE));
        $levelOne->assignAttribute($this->getReference(BrandFixture::REFERENCE_ATTRIBUTE_BRAND));
        $levelOne->assignAttribute($this->getReference(ColorFixture::REFERENCE_ATTRIBUTE_COLOR));
        $levelOne->assignAttribute($this->getReference(TypeSubjectFixture::REFERENCE_ATTRIBUTE_TYPE_SUBJECT));

        $manager->persist($levelOne);

        $levelTwo = new Category();
        $levelTwo->setName('Леггинсы черные');
        $levelTwo->setParent($levelOne);
        $levelTwo->setSlug($this->slugify->generate(self::REFERENCE_LEGGINGS_BLACK));
        $this->setReference(self::REFERENCE_LEGGINGS_BLACK, $levelTwo);
        $manager->persist($levelTwo);

        $levelOne = new Category();
        $levelOne->setName($name = 'Брюки');
        $levelOne->setParent($levelRoot);
        $levelOne->setSlug($this->slugify->generate(self::REFERENCE_PANTS));
        $levelOne->setPrefixSlugProduct($this->slugify->generate($name));
        $this->setReference(self::REFERENCE_PANTS, $levelOne);
        $manager->persist($levelOne);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BrandFixture::class,
            ColorFixture::class,
            SizeFixture::class,
            TypeSubjectFixture::class,
        ];
    }
}
