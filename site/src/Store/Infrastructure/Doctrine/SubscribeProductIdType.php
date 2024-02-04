<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Doctrine;

use App\Common\Infrastructure\Doctrine\UuidType;
use App\Store\Domain\Entity\Product\ValueObject\SubscribeProductId;

class SubscribeProductIdType extends UuidType
{
    public function getName(): string
    {
        return 'store_product_subscribe_uuid';
    }

    protected function typeClassName(): string
    {
        return SubscribeProductId::class;
    }
}
