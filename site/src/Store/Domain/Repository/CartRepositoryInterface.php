<?php

declare(strict_types=1);

namespace App\Store\Domain\Repository;

use App\Store\Domain\Entity\Cart\Cart;

interface CartRepositoryInterface
{
    public function find(string $id): ?Cart;

    public function save(Cart $cart): void;
}
