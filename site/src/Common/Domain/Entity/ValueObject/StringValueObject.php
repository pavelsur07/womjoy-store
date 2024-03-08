<?php

declare(strict_types=1);

namespace App\Common\Domain\Entity\ValueObject;

abstract class StringValueObject
{
    /**
     * @var string
     */
    protected $value;

    public function __construct(string $value = '')
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value();
    }

    final public function value(): ?string
    {
        return $this->value;
    }
}
