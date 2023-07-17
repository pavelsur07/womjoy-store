<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Doctrine;

use App\Common\Infrastructure\Doctrine\UuidType;
use App\Store\Domain\Entity\Order\ValueObject\OrderId;

class OrderIdType extends UuidType
{
    public function getName(): string
    {
        return 'store_order_uuid';
    }

    protected function typeClassName(): string
    {
        return OrderId::class;
    }
}
