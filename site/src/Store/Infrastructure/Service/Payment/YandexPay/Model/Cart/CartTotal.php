<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Model\Cart;

use App\Store\Infrastructure\Service\Payment\YandexPay\Model\AbstractObject;

class CartTotal extends AbstractObject
{
    /**
     * Стоимость корзины с учетом всех скидок, и без учета доставки
     * Example: 123.45.
     */
    private float $amount;

    /**
     * Max length: 2048.
     */
    private ?string $label = null;

    public function __construct(float $amount)
    {
        $this->amount = $amount;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }
}
