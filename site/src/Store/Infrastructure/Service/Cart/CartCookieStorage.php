<?php

namespace App\Store\Infrastructure\Service\Cart;

use App\Store\Domain\Entity\Cart\Cart;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

readonly class CartCookieStorage implements CartStorageInterface
{
    public function __construct(
        private ContainerInterface $container,
    )
    {
    }

    public function getCart(): ?Cart
    {
        $cookies = $this->container->get('request_stack');


        $cartKey = $cookies->getMainRequest()->cookies->get('');
        // TODO: Implement getCart() method.
        return null;
    }

    public function setCart(Cart $cart): void
    {

        // TODO: Implement setCart() method.
    }
}