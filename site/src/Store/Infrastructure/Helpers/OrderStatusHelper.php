<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Helpers;

use App\Store\Domain\Entity\Order\ValueObject\OrderStatus;

class OrderStatusHelper
{
    public static function orderStatusHelper(string $status): string
    {
        return match ($status) {
            OrderStatus::NEW => 'Заказ оформлен',
            OrderStatus::SENT => 'Заказ отправлен',
            OrderStatus::PAID => 'Заказ оформлен  и оплачен',
            OrderStatus::DELIVERED => 'Заказ доставлен и готов к получению',
            OrderStatus::COMPLETED => 'Заказ получен',
            OrderStatus::CANCELLED_BY_CUSTOMER => 'Заказ отменен закчиком',
            OrderStatus::CANCELLED => 'Заказ отменен продавцом',
            default => 'unknown status code',
        };
    }
}
