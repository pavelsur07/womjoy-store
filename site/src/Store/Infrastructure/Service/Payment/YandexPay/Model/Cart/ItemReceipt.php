<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Model\Cart;

use App\Store\Infrastructure\Service\Payment\YandexPay\Model\AbstractObject;

class ItemReceipt extends AbstractObject
{
    private ?Agent $agent = null;

    /**
     * Не должно содержать больше двух знаков после запятой. Например: 1.12, 5.1, 10, 11.00 .
     * Example: 123.45.
     */
    private ?float $excise = null;

    private ?MarkQuantity $markQuantity = null;

    /**
     * Описание значений: Ссылка
     * Enum: 0, 10, 11, 12, 20, 21, 22, 30, 31, 32, 40, 41, 42, 50, 51, 70, 71, 72, 73, 80, 81, 82, 83, 255, null.
     */
    private ?int $measure = null;

    /**
     * Описание значений: Ссылка
     * Enum: 1, 2, 3, 4, 5, 6, 7, null.
     *
     * @see https://pay.yandex.ru/ru/docs/custom/fns#payment-method-type
     */
    private ?int $paymentMethodType = null;

    /**
     * Описание значений: Ссылка
     * Enum: 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, null.
     *
     * @see https://pay.yandex.ru/ru/docs/custom/fns#payment-subject-type
     */
    private ?int $paymentSubjectType = null;

    /**
     * Код товара (base64 кодированный массив от 1 до 32 байт).
     */
    private ?string $productCode = null;

    private ?Supplier $supplier = null;

    /**
     * Описание значений: Ссылка
     * Enum: 1, 2, 3, 4, 5, 6.
     *
     * @see https://pay.yandex.ru/ru/docs/custom/fns#tax
     */
    private int $tax;

    /**
     * Max length: 2048.
     */
    private ?string $title = null;

    public function __construct(int $tax)
    {
        $this->tax = $tax;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    public function getExcise(): ?float
    {
        return $this->excise;
    }

    public function setExcise(?float $excise): self
    {
        $this->excise = $excise;

        return $this;
    }

    public function getMarkQuantity(): ?MarkQuantity
    {
        return $this->markQuantity;
    }

    public function setMarkQuantity(?MarkQuantity $markQuantity): self
    {
        $this->markQuantity = $markQuantity;

        return $this;
    }

    public function getMeasure(): ?int
    {
        return $this->measure;
    }

    public function setMeasure(?int $measure): self
    {
        $this->measure = $measure;

        return $this;
    }

    public function getPaymentMethodType(): ?int
    {
        return $this->paymentMethodType;
    }

    public function setPaymentMethodType(?int $paymentMethodType): self
    {
        $this->paymentMethodType = $paymentMethodType;

        return $this;
    }

    public function getPaymentSubjectType(): ?int
    {
        return $this->paymentSubjectType;
    }

    public function setPaymentSubjectType(?int $paymentSubjectType): self
    {
        $this->paymentSubjectType = $paymentSubjectType;

        return $this;
    }

    public function getProductCode(): ?string
    {
        return $this->productCode;
    }

    public function setProductCode(?string $productCode): self
    {
        $this->productCode = $productCode;

        return $this;
    }

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): self
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getTax(): int
    {
        return $this->tax;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
