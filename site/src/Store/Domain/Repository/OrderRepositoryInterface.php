<?php

declare(strict_types=1);

namespace App\Store\Domain\Repository;

use App\Store\Domain\Entity\Order\Order;
use App\Store\Domain\Entity\Order\ValueObject\OrderId;

interface OrderRepositoryInterface
{
    public function get(OrderId $id): Order;

    public function find(string $id): ?Order;

    public function save(Order $order): void;
}
