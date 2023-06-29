<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Cart;

use App\Store\Domain\Entity\Cart\Cart;
use App\Store\Infrastructure\Repository\CartRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartSessionStorage
{
    public const CART_KEY_NAME = 'cart_id';

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly CartRepository $cartRepository,
    ) {
    }

    public function getCart(): ?Cart
    {
        return $this->cartRepository->findById(
            id: $this->getCartId(),
        );
    }

    public function setCart(Cart $cart): void
    {
        $this->getSession()->set(self::CART_KEY_NAME, $cart->getId());
    }

    private function getCartId(): ?int
    {
        return $this->getSession()->get(self::CART_KEY_NAME);
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}
