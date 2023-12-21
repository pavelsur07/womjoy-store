<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Doctrine;

use App\Common\Infrastructure\Doctrine\UuidType;
use App\Store\Domain\Entity\Message\ValueObject\MessageId;

class MessageIdType extends UuidType
{
    public function getName(): string
    {
        return 'store_message_uuid';
    }

    protected function typeClassName(): string
    {
        return MessageId::class;
    }
}
