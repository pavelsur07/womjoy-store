<?php

declare(strict_types=1);

namespace App\Store\Tests\Unit\Domain\Cart;

use App\Store\Domain\Entity\Cart\Cart;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Domain\Entity\Product\ValueObject\ProductPrice;
use App\Store\Domain\Entity\Product\Variant;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

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
