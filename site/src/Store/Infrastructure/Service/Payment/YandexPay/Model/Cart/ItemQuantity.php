<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Model\Cart;

use App\Store\Infrastructure\Service\Payment\YandexPay\Model\AbstractObject;

class ItemQuantity extends AbstractObject
{
    /**
     * Максимально доступное количество товара
     * Example: 123.45.
     */
    private ?float $available = null;

    /**
     * Количество товара в заказе
     * Example: 123.45.
     */
    private float $count;

    /**
     * Название единиц измерения, например "кг" или "шт"
     * Max length: 2048.
     */
    private ?string $label = null;

    public function __construct(float $count)
    {
        $this->count = $count;
    }

    public function getAvailable(): ?float
    {
        return $this->available;
    }

    public function setAvailable(?float $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function getCount(): float
    {
        return $this->count;
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
