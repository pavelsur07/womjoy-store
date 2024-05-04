<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Cart\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
class CartPromoCode
{
    public const PERCENT = 'percent';
    public const FIXED = 'fixed';

    #[ORM\Column(type: 'string', length: 15, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(type: 'string', length: 15, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $value = null;

    public function __construct(string $promoCode, string $type, int $value)
    {
        Assert::oneOf($type, self::list());
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

    public static function list(): array
    {
        return
            [
                self::PERCENT,
                self::FIXED,
            ];
    }
}
