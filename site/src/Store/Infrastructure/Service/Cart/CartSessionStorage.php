<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Cart;

use App\Store\Domain\Entity\Cart\Cart;
use App\Store\Infrastructure\Repository\CartRepository;
use LogicException;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CartSessionStorage
{
    public const CART_KEY_NAME = 'cart_id';

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ContainerInterface $container,
        private readonly CartRepository $cartRepository,
    ) {
    }

    public function getCart(): ?Cart
    {
        /** @var UserInterface $user */
        if ($user = $this->getUser() !== null) {
            return $this->cartRepository->findByOwner($user->getId());
        }

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

    private function getUser(): ?UserInterface
    {
        if (!$this->container->has('security.token_storage')) {
            throw new LogicException('The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".');
        }

        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return null;
        }

        return $token->getUser();
    }
}
