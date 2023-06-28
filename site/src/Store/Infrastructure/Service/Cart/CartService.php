<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Cart;

use App\Store\Domain\Entity\Cart\Cart;
use App\Store\Infrastructure\Repository\CartRepository;
use DateTimeImmutable;

readonly class CartService
{
    public function __construct(
        private CartSessionStorage $storage,
        private CartRepository $carts,
    ) {
    }

    public function getCurrentCart(): Cart
    {
        $cart = $this->storage->getCart();

        if (!$cart) {
            $cart = new Cart(createdAt: new DateTimeImmutable());
            $this->save($cart);
            $this->storage->setCart($cart);
        }

        return $cart;
    }

    public function save(Cart $cart): void
    {
        $this->carts->save($cart, true);
    }

    public function add(): void
    {
    }

    public function set(): void
    {
    }

    public function remove(): void
    {
    }

    public function clear(): void
    {
    }
}
