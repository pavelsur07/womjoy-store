<?php

declare(strict_types=1);

namespace App\Store\Tests\Builder;

use App\Store\Domain\Entity\Product\Product;
use App\Store\Domain\Entity\Product\ValueObject\ProductPrice;

class ProductBuilder
{
    public const PRODUCT_NAME = 'product name';

    public function build(): Product
    {
        $product =  new Product(price: new ProductPrice(1000));
        $product->setName(self::PRODUCT_NAME);
        return $product;
    }
}
