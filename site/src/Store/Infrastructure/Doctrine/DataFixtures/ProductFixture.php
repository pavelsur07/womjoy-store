<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Doctrine\DataFixtures;

use App\Common\Infrastructure\Service\Slugify\SlugifyService;
use App\Store\Domain\Entity\Category\Category;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Domain\Entity\Product\ValueObject\ProductPrice;
use App\Store\Domain\Entity\SeoMetadata;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly SlugifyService $slug)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $i = 1000;
        $mainCategory = $this->getReference(CategoryFixture::REFERENCE_LEGGINGS);

        $product = $this->productCreate(
            category: $mainCategory,
            name: 'Product name',
            description: 'Product description',
            price: 1000,
            number: $i
        );

        /*
        $product = new Product(new ProductPrice(1000));
        $product->setArticle('ARTICLE-' . $i);
        $product->setName($name = 'product name');
        $product->setDescription('product description');
        $product->setSeoMetadata(
            new SeoMetadata(
                title: 'seo title',
                description: 'seo description',
            )
        );
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        */

        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setArticle('ARTICLE-' . $i);
        $product->setName($name = 'product name');
        $product->setDescription('product description');
        $product->setSeoMetadata(
            new SeoMetadata(
                title: 'seo title',
                description: 'seo description',
            )
        );
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setArticle('ARTICLE-' . $i);
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setSeoMetadata(
            new SeoMetadata(
                title: 'seo title',
                description: 'seo description',
            )
        );
        $product->setMainCategory($mainCategory);
        $product->setArticle('ARTICLE-' . $i);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setArticle('ARTICLE-' . $i);
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setSeoMetadata(
            new SeoMetadata(
                title: 'seo title',
                description: 'seo description',
            )
        );
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setArticle('ARTICLE-' . $i);
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setSeoMetadata(
            new SeoMetadata(
                title: 'seo title',
                description: 'seo description',
            )
        );
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setArticle('ARTICLE-' . $i);
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setSeoMetadata(
            new SeoMetadata(
                title: 'seo title',
                description: 'seo description',
            )
        );
        $product->setMainCategory($mainCategory);
        $product->setArticle('ARTICLE-' . $i);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setArticle('ARTICLE-' . $i);
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setSeoMetadata(
            new SeoMetadata(
                title: 'seo title',
                description: 'seo description',
            )
        );
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();

        $mainCategory = $this->getReference(CategoryFixture::REFERENCE_PANTS);

        $product = new Product(new ProductPrice(1000));
        $product->setArticle('ARTICLE-' . $i);
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setSeoMetadata(
            new SeoMetadata(
                title: 'seo title',
                description: 'seo description',
            )
        );
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setSeoMetadata(
            new SeoMetadata(
                title: 'seo title',
                description: 'seo description',
            )
        );
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setSeoMetadata(
            new SeoMetadata(
                title: 'seo title',
                description: 'seo description',
            )
        );
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setArticle('ARTICLE-' . $i);
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setSeoMetadata(
            new SeoMetadata(
                title: 'seo title',
                description: 'seo description',
            )
        );
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();
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
        ];
    }
}
