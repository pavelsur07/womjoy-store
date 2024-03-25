<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Model\Cart;

use App\Store\Infrastructure\Service\Payment\YandexPay\Model\AbstractObject;

class RenderedCart extends AbstractObject
{
    /**
     * Переданный продавцом идентификатор корзины
     * Max length: 2048.
     */
    private string $externalId;

    /**
     * @var RenderedCartItem[]
     */
    private array $items;

    private CartTotal $total;

    public function __construct(string $externalId, array $items, CartTotal $total)
    {
        $this->externalId = $externalId;
        $this->items = $items;
        $this->total = $total;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotal(): CartTotal
    {
        return $this->total;
    }
}
