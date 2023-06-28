<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Order\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class OrderItemPrice
{
    #[ORM\Column]
    private int $salePrice;

    #[ORM\Column]
    private string $currency;

    #[ORM\Column]
    private int $currencyRate = 1;

    public function __construct(int $salePrice, string $currency = 'RUB', int $currencyRate = 1)
    {
        $this->salePrice = $salePrice;
        $this->currency = $currency;
        $this->currencyRate = $currencyRate;
    }

    public function getSalePrice(): int
    {
        return $this->salePrice;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getCurrencyRate(): int
    {
        return $this->currencyRate;
    }
}
