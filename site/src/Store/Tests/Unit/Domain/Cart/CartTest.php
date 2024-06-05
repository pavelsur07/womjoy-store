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

        $cart->setPromoCode(
            new CartPromoCode(
                promoCode: 'PROMO',
                type: CartPromoCode::PERCENT,
                value: $discount =  3
            )
        );

        self::assertEquals($discount, $cart->getPromoCode()->getValue());
        $promoCodeDiscount = round($subtotal * $cart->getPromoCode()->getValue(), 2)/100;
        self::assertEquals($promoCodeDiscount, $cart->getPromoCodeDiscount());
    }

    public function testNotPromoCodeDiscount(): void
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

        self::assertEquals(0, $cart->getPromoCodeDiscount());
    }

    public function testCustomer(): void
    {
        $now = new DateTimeImmutable();
        $cart = new Cart(
            createdAt: $now,
            customerId: null
        );

        self::assertNotNull($cart->getCustomer());

        $cart->getCustomer()->setEmail($emil = 'email@email.com');
        $cart->getCustomer()->setAddress($address = 'address@address.com');
        $cart->getCustomer()->setName($name = 'name');

        self::assertEquals($emil, $cart->getCustomer()->getEmail());
        self::assertEquals($address, $cart->getCustomer()->getAddress());
        self::assertEquals($name, $cart->getCustomer()->getName());
    }
}
