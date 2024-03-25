<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Model\Cart;

use App\Store\Infrastructure\Service\Payment\YandexPay\Model\AbstractObject;

class RenderedCartItem extends AbstractObject
{
    /**
     * Описание товара
     * Max length: 2048
     */
    private ?string $description = null;

    /**
     * Цена за единицу товара с учётом скидок на позицию
     * Example: 123.45
     */
    private ?float $discountedUnitPrice = null;

    /**
     * Id товара в системе продавца. В параметрах запроса каждый идентификатор товара productId должен быть уникальным
     * Max length: 2048
     */
    private string $productId;

    /**
     * Количество товара в заказе
     */
    private ItemQuantity $quantity;

    /**
     * Данные для формирования чека
     */
    private ?ItemReceipt $receipt = null;

    /**
     * Суммарная цена за позицию без учета скидок
     * Example: 123.45
     */
    private ?float $subtotal = null;

    /**
     * Наименование товара
     * Max length: 2048
     */
    private string $title;

    /**
     * Суммарная цена за позицию с учётом скидок на позицию
     * Example: 123.45
     */
    private float $total;

    /**
     * Полная цена за единицу товара без учетка скидки
     * Example: 123.45
     */
    private ?float $unitPrice = null;

    public function __construct(string $productId, ItemQuantity $quantity, string $title, float $total)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->title = $title;
        $this->total = $total;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDiscountedUnitPrice(): ?float
    {
        return $this->discountedUnitPrice;
    }

    public function setDiscountedUnitPrice(?float $discountedUnitPrice): self
    {
        $this->discountedUnitPrice = $discountedUnitPrice;

        return $this;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getQuantity(): ItemQuantity
    {
        return $this->quantity;
    }

    public function getReceipt(): ?ItemReceipt
    {
        return $this->receipt;
    }

    public function setReceipt(?ItemReceipt $receipt): self
    {
        $this->receipt = $receipt;

        return $this;
    }

    public function getSubtotal(): ?float
    {
        return $this->subtotal;
    }

    public function setSubtotal(?float $subtotal): self
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(?float $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }
}
