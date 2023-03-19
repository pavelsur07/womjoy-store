<?php

declare(strict_types=1);

namespace App\DataFixtures\Store;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setPrice(100);
        $product->setStatus('draft');
        $product->setPrice(1000);
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product();
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setPrice(100);
        $product->setStatus('draft');
        $product->setPrice(1000);
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product();
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setPrice(100);
        $product->setStatus('draft');
        $product->setPrice(1000);
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product();
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setPrice(100);
        $product->setStatus('draft');
        $product->setPrice(1000);
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product();
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setPrice(100);
        $product->setStatus('draft');
        $product->setPrice(1000);
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product();
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setPrice(100);
        $product->setStatus('draft');
        $product->setPrice(1000);
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product();
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setPrice(100);
        $product->setStatus('draft');
        $product->setPrice(1000);
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product();
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setPrice(100);
        $product->setStatus('draft');
        $product->setPrice(1000);
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product();
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setPrice(100);
        $product->setStatus('draft');
        $product->setPrice(1000);
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product();
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setPrice(100);
        $product->setStatus('draft');
        $product->setPrice(1000);
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();

        $product = new Product();
        $product->setName('product name');
        $product->setDescription('product description');
        $product->setPrice(100);
        $product->setStatus('draft');
        $product->setPrice(1000);
        $product->setSeoTitle('seo title');
        $product->setSeoDescription('seo description');
        $manager->persist($product);
        $manager->flush();
    }
}
