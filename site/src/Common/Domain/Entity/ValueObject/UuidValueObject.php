<?php

declare(strict_types=1);

namespace App\Common\Domain\Entity\ValueObject;

use Webmozart\Assert\Assert;

abstract class UuidValueObject
{
    protected string $value;

    public function __construct(string $uuid)
    {
        Assert::uuid($uuid);

        $this->value = $uuid;
    }

    public function __toString(): string
    {
        return $this->value();
    }

    final public function value(): string
    {
        return $this->value;
    }

    final public function equals(self $uuid): bool
    {
        return $this->value() === $uuid->value();
    }
}
