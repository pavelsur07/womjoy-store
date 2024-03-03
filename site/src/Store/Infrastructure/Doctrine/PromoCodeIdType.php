<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Doctrine;

use App\Common\Infrastructure\Doctrine\UuidType;
use App\Store\Domain\Entity\Promo\ValueObject\PromoCodeId;

class PromoCodeIdType extends UuidType
{
    public function getName(): string
    {
        return 'store_promo_code_uuid';
    }

    protected function typeClassName(): string
    {
        return PromoCodeId::class;
    }
}
