<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Doctrine\DataFixtures;

use App\Store\Domain\Entity\Product\Product;
use App\Store\Domain\Entity\Product\ValueObject\ProductPrice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setStatus('draft');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setStatus('draft');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setStatus('draft');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setStatus('draft');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setStatus('draft');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setStatus('draft');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setStatus('draft');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setStatus('draft');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setStatus('draft');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setStatus('draft');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product(new ProductPrice(1000));
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setStatus('draft');
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();
    }
}
