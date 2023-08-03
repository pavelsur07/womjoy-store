<?php

declare(strict_types=1);

namespace App\Common\Domain\Entity\ValueObject;

abstract class IntegerValueObject
{
    protected ?int $value = null;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    final public function value(): int
    {
        return $this->value;
    }
}
