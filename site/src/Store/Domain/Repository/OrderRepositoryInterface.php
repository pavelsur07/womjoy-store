<?php

declare(strict_types=1);

namespace App\Store\Domain\Repository;

use App\Store\Domain\Entity\Order\Order;

interface OrderRepositoryInterface
{
    public function find(string $id): ?Order;

    public function save(Order $order): void;
}
