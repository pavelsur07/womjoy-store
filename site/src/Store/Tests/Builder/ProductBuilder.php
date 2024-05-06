<?php

declare(strict_types=1);

namespace App\Store\Tests\Builder;

use App\Store\Domain\Entity\Product\Product;
use App\Store\Domain\Entity\Product\ValueObject\ProductPrice;
use App\Store\Domain\Entity\Product\Variant;
use ReflectionClass;

class ProductBuilder
{
    public const PRODUCT_NAME = 'product name';

    public string $name = self::PRODUCT_NAME;
    public int $id = 1;
    public int $price = 1000;

    public string $variantArticle = 'ARTICLE001';
    public string $variantValue = 'XS';
    public int $variantId = 1;

    public int $variantQuantity = 10;

    public function createVariant(Product $product): Variant
    {
        $variant = new Variant(product: $product, article: 'ARTICLE001', value: '', barcode: '');
        $reflection = new ReflectionClass($variant);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($variant, $this->id);

        return $variant;
    }

    public function build(): Product
    {
        $product =  new Product(
            price: new ProductPrice(price: $this->price)
        );
        $product->setName(name: $this->name);
        $reflection = new ReflectionClass($product);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($product, $this->id);

        $product->addVariantNewVersion($variant = $this->createVariant($product));
        $variant->changeQuantity($this->variantQuantity);
        return $product;
    }
}
