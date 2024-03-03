<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Promo;

use App\Store\Domain\Entity\Promo\PromoCode;
use App\Store\Domain\Entity\Promo\ValueObject\PromoCodeDiscountType;
use App\Store\Domain\Entity\Promo\ValueObject\PromoCodeId;
use App\Store\Infrastructure\Repository\PromoCodeRepository;

class PromoCodeService
{
    private PromoCode $code;

    public function __construct(
        private readonly PromoCodeRepository $promoCodes,
    ) {}

    public function getPromoCode(int $discountValue, string $discountType = PromoCodeDiscountType::SUM): PromoCode
    {
        $code = $this->generate();
        $this->code = new PromoCode(
            id: PromoCodeId::generate(),
            code: $code,
            discountType: new PromoCodeDiscountType($discountType),
            discountValue: $discountValue
        );

        return $this->code;
    }

    public function getCode(): PromoCode
    {
        return $this->code;
    }

    public function save(): void
    {
        $this->promoCodes->save($this->code, true);
    }

    public function generate(): string
    {
        $code = PromoCode::generate();
        if ($this->promoCodes->findByPromoCode($code) !== null) {
            return $this->generate();
        }

        return $code;
    }
}
