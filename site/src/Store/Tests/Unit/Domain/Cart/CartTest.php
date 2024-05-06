<?php

declare(strict_types=1);

namespace App\Store\Tests\Unit\Domain\Cart;

use App\Store\Domain\Entity\Cart\Cart;
use App\Store\Domain\Entity\Cart\ValueObject\CartPromoCode;
use App\Store\Tests\Builder\ProductBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CartTest extends TestCase
{
    public function testCreateCart(): void
    {
        $now = new DateTimeImmutable();
        $cart = new Cart(
            createdAt: $now,
            customerId: null
        );

        \assert($cart instanceof Cart);
        self::assertEquals($now, $cart->getCreatedAt());

        $product =  (new ProductBuilder())->build();

        self::assertEquals(1, $product->getId());
        self::assertCount(1, $product->getVariants());

        $variant = $product->getVariants()->first();

        $cart->add($variant, 1);
        self::assertEquals($now, $cart->getCreatedAt());
        self::assertCount(1, $cart->getItems());

        self::assertEquals($subtotal = 1000, $cart->getSubtotal());

        $cart->setPromoCode(new CartPromoCode(promoCode: 'PROMO', type: CartPromoCode::PERCENT, value: $discount =  5));
        self::assertEquals($discount, $cart->getPromoCode()->getValue());
        self::assertEquals($discountValue = round($subtotal/100 * $cart->getPromoCode()->getValue()), $cart->getPromoCodeDiscount());
        self::assertEquals($subtotal - $discountValue, $cart->getSubtotal() - $discountValue);
    }
}
