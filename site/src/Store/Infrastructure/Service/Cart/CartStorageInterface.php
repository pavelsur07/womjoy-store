<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Cart;

use App\Store\Domain\Entity\Cart\Cart;

interface CartStorageInterface
{
    public function getCart(): ?Cart;

    public function setCart(Cart $cart): void;
}
