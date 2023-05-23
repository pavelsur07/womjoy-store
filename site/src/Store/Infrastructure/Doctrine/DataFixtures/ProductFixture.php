<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Doctrine\DataFixtures;

use App\Common\Infrastructure\Service\Slugify\SlugifyService;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Domain\Entity\Product\ValueObject\ProductPrice;
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
        $mainCategory = $this->getReference(CategoryFixture::REFERENCE_WOMAN);

        $product = new Product(new ProductPrice(1000));
        $product->setName($name = 'product name');
        $product->setDescription('product description');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $product->setMainCategory($mainCategory);
        $product->setSlug($this->slug->generate($name . $i++));
        $manager->persist($product);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixture::class,
        ];
    }
}
