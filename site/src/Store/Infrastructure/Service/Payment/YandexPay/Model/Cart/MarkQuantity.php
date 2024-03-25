<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Model\Cart;

use App\Store\Infrastructure\Service\Payment\YandexPay\Model\AbstractObject;

class MarkQuantity extends AbstractObject
{
    private int $denominator;
    private int $numerator;

    public function __construct(int $denominator, int $numerator)
    {
        $this->denominator = $denominator;
        $this->numerator = $numerator;
    }

    public function getDenominator(): int
    {
        return $this->denominator;
    }

    public function getNumerator(): int
    {
        return $this->numerator;
    }
}
