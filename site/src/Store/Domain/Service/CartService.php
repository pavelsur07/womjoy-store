<?php

declare(strict_types=1);

namespace App\Store\Domain\Service;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Cart\Cart;
use App\Store\Domain\Repository\CartRepositoryInterface;

readonly class CartService
{
    public function __construct(
        private CartRepositoryInterface $carts,
        private Flusher $flusher,
    ) {
    }

    public function get(string $id): Cart
    {
        $cart = $this->carts->find($id);
        if ($cart === null) {
            $cart = new Cart($id);
            $this->flusher->flush();
        }

        return $cart;
    }
}
