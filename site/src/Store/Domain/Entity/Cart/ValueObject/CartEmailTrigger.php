<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Cart\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class CartEmailTrigger
{
    public const FIRST = 'first';
    public const SECOND = 'second';
    public const THIRD = 'third';

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private ?string $value = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $errorMessage = null;

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function isFirstSendEmail(): bool
    {
        if ($this->value === self::FIRST) {
            return true;
        }

        if ($this->value === self::SECOND) {
            return true;
        }

        if ($this->value === self::THIRD) {
            return true;
        }

        return false;
    }

    public function isSecondSendEmail(): bool
    {
        if ($this->value === self::SECOND) {
            return true;
        }

        if ($this->value === self::THIRD) {
            return true;
        }

        return false;
    }

    public function isThirdSendEmail(): bool
    {
        if ($this->value === self::THIRD) {
            return true;
        }

        return false;
    }

    public function firstSendEmail(): void
    {
        $this->value= self::FIRST;
    }

    public function secondSendEmail(): void
    {
        $this->value = self::SECOND;
    }

    public function thirdSendEmail(): void
    {
        $this->value = self::THIRD;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(?string $errorMessage): void
    {
        $this->errorMessage = $errorMessage;
    }
}
