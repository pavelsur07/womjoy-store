<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product\ValueObject;

use App\Common\Domain\Entity\ValueObject\UuidValueObject;
use Ramsey\Uuid\Uuid;

class SubscribeProductId extends UuidValueObject
{
    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
}
