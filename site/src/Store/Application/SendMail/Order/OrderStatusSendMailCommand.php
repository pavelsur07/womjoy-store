<?php

declare(strict_types=1);

namespace App\Store\Application\SendMail\Order;

readonly class OrderStatusSendMailCommand
{
    public function __construct(
        private string $orderUuid,
    ) {}

    public function getOrderUuid(): string
    {
        return $this->orderUuid;
    }
}
