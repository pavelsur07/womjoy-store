<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Cart;

use App\Common\Infrastructure\Helper\SessionHelper;
use App\Store\Domain\Entity\Cart\Cart;
use App\Store\Infrastructure\Repository\CartRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

readonly class CartSessionStorage
{
    public function __construct(
        private RequestStack $requestStack,
        private CartRepository $cartRepository,
    ) {}

    public function getCart(): ?Cart
    {
        return $this->cartRepository->findById(
            id: $this->getCartId(),
        );
    }

    public function setCart(Cart $cart): void
    {
        $this->getSession()->set(SessionHelper::CART_KEY, $cart->getId());
    }

    private function getCartId(): ?int
    {
        return $this->getSession()->get(SessionHelper::CART_KEY);
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}
