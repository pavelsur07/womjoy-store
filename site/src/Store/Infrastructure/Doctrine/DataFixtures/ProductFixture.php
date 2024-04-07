<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Doctrine\DataFixtures;

use App\Common\Infrastructure\Service\Slugify\SlugifyService;
use App\Store\Domain\Entity\Category\Category;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Domain\Entity\Product\ValueObject\ProductPrice;
use App\Store\Domain\Entity\SeoMetadata;
use App\Store\Infrastructure\Doctrine\DataFixtures\Category\CategoryFixture;
use App\Store\Infrastructure\Doctrine\DataFixtures\Category\CategoryWidgetFixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE_PRODUCT = 'reference-product';

    public function __construct(private readonly SlugifyService $slug) {}

    public function load(ObjectManager $manager): void
    {
        $i = 1000;
        $mainCategory = $this->getReference(CategoryFixture::REFERENCE_LEGGINGS);
        $slaveCategory = $this->getReference(CategoryWidgetFixture::REFERENCE_NEW_ARRIVAL);

        $product = $this->productCreate(
            category: $mainCategory,
            name: 'Легинсы с высокой посадкой белые',
            description: 'Легинсы с высокой посадкой выполнены из эластичного бифлекса. Широкий пояс обеспечивает комфортную посадку по фигуре.',
            price: 1000,
            number: $i
        );

        // $product->assignCategory($slaveCategory);

        $manager->persist($product);
        $product->addVariant('XL', 'BARCODE000000001');
        $product->addVariant('XXL', 'BARCODE000000002');
        $this->setReference(self::REFERENCE_PRODUCT, $product);
        $manager->flush();
        // ----------------------------------
    }

    public function productCreate(
        Category $category,
        string $name,
        string $description,
        int $price,
        int $number
    ): Product {
        $product = new Product(new ProductPrice($price));
        $product->setArticle('ARTICLE-' . $number);
        $product->setName($name);
        $product->setDescription($description);
        $product->setSeoMetadata(
            new SeoMetadata(
                title: 'seo title - ' . $name,
                description: 'seo description - ' . $description,
            )
        );
        $product->setMainCategory($category);
        $product->setSlug($this->slug->generate($name . $number));
        $product->active();
        return $product;
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixture::class,
            CategoryWidgetFixture::class,
        ];
    }
}
