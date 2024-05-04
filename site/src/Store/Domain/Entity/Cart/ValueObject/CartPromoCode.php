<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Cart\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class CartPromoCode
{
    private ?string $code = null;
    private ?string $type = null;

    private ?int $value = null;

    public function __construct(?string $promoCode, ?string $type, ?int $value)
    {
        $this->code = $promoCode;
        $this->type = $type;
        $this->value = $value;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }
}
