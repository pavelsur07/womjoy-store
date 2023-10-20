<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Order\ValueObject;

use DateTimeImmutable;
use Webmozart\Assert\Assert;

class OrderStatus
{
    public const NEW = 'new';
    public const PAID = 'paid';
    public const SENT = 'sent';
    public const COMPLETED = 'completed';
    public const CANCELLED = 'cancelled';
    public const CANCELLED_BY_CUSTOMER = 'cancelled_by_customer';

    public function __construct(
        private readonly string $value,
        private readonly DateTimeImmutable $createdAt,
    ) {
        Assert::oneOf($this->value, self::list());
    }

    public function isPaid(): bool
    {
        return $this->value === self::PAID;
    }

    public function isSent(): bool
    {
        return $this->value === self::SENT;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public static function list(): array
    {
        return [
            self::NEW,
            self::PAID,
            self::SENT,
            self::COMPLETED,
            self::CANCELLED,
            self::CANCELLED_BY_CUSTOMER,
        ];
    }
}
