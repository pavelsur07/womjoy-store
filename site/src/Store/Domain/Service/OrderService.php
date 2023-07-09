<?php

declare(strict_types=1);

namespace App\Store\Domain\Service;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Order\Order;
use App\Store\Domain\Repository\OrderRepositoryInterface;

readonly class OrderService
{
    public function __construct(
        private OrderRepositoryInterface $orders,
        private Flusher $flusher,
    ) {
    }

    public function get(string $id): Order
    {
        $order = $this->orders->find($id);
        if ($order === null) {
            $order = new Order(null);
            $this->flusher->flush();
        }

        return $order;
    }
}
