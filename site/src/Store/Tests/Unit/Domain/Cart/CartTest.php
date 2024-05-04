<?php

declare(strict_types=1);

namespace App\Store\Tests\Unit\Domain\Cart;

use App\Store\Domain\Entity\Cart\Cart;
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
    }
}
