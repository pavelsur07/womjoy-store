<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Cart;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Cart\Cart;
use App\Store\Infrastructure\Repository\CartRepository;
use DateTimeImmutable;

readonly class CartService
{
    public function __construct(
        private CartSessionStorage $storage,
        private CartRepository $carts,
        private Flusher $flusher,
    ) {
    }

    public function getCurrentCart(int|null $customerId = null): Cart
    {
        // 1. Ищем корзину по id если нет корзины создаем и возвращаем
        // 2. Если пользователь залогинин то ищем корзину по идентификатору пользователя
        // 3. Если корзина не найдена создаем корзину с идентификатором пользователя

        $cart = $this->storage->getCart();

        // Если корзина найдена и пользователь не null
        if ($cart !== null && $customerId !== null) {
            // Если пользователь у текущей корзины не установлены
            if ($cart->getCustomerId() === null) {
                // Устанавливаем значение customerId
                $cart->setCustomerId($customerId);
                $this->flusher->flush();
            }
        }

        if (!$cart) {
            $cart = new Cart(createdAt: new DateTimeImmutable(), customerId: $customerId);
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

    public function clear(?int $customerId = null): void
    {
        $cart = $this->getCurrentCart($customerId);
        $cart->clear();

        $this->flusher->flush();
    }
}
