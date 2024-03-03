<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Promo;

use App\Store\Domain\Entity\Promo\ValueObject\PromoCodeDiscountType;
use App\Store\Domain\Entity\Promo\ValueObject\PromoCodeId;
use App\Store\Domain\Exception\StoreException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'store_promo_code')]
class PromoCode
{
    #[ORM\Column(type: 'store_promo_code_uuid')]
    #[ORM\Id]
    private PromoCodeId $id;

    #[ORM\Column(type: 'string', unique: true)]
    private string $code;

    #[ORM\Embedded(class: PromoCodeDiscountType::class, columnPrefix: 'discount_type_')]
    private PromoCodeDiscountType $discountType;

    #[ORM\Column(type: 'integer')]
    private int $discountValue = 0;

    #[ORM\Column(type: 'boolean')]
    private bool $isActivated = false;

    public function __construct(PromoCodeId $id, string $code, PromoCodeDiscountType $discountType, int $discountValue)
    {
        $this->id = $id;
        $this->code = $code;
        $this->discountType = $discountType;
        $this->discountValue = $discountValue;
    }

    public function getId(): PromoCodeId
    {
        return $this->id;
    }

    public function activated(): void
    {
        if ($this->isActivated) {
            throw new StoreException('Promo code already activated!');
        }

        $this->isActivated = true;
    }

    public function isEqual(string $promoCode): bool
    {
        if ($promoCode === $this->code) {
            return true;
        }

        return false;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDiscountType(): PromoCodeDiscountType
    {
        return $this->discountType;
    }

    public function getDiscountValue(): int
    {
        return $this->discountValue;
    }

    public function isExpired(): bool
    {
        return $this->isActivated;
    }

    public function generate(): string
    {
        return 'promo-code';
    }
}
