<?php

declare(strict_types=1);

namespace App\Store\Tests\Unit\Domain\Product;

use App\Store\Tests\Builder\AttributeBuilder;
use App\Store\Tests\Builder\AttributeVariantBuilder;
use App\Store\Tests\Builder\ProductBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ProductAttributeTest extends TestCase
{
    public function testProductAssignAttribute(): void
    {
        $product = (new ProductBuilder())->build();
        $attribute = (new AttributeBuilder())->build();
        $attributeVariant = (new AttributeVariantBuilder())->build();

        // $product->assignAttribute($attribute, $attributeVariant);

        self::assertEquals(ProductBuilder::PRODUCT_NAME, $product->getName());
        self::assertEquals(AttributeBuilder::ATTRIBUTE_NAME, $attribute->getName());
        self::assertEquals(AttributeVariantBuilder::ATTRIBUTE_VARIANT_NAME, $attributeVariant->getName());
    }
}
