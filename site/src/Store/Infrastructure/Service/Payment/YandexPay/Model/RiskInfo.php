<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Model;

class RiskInfo extends AbstractObject
{
    public const SHIPPING_TYPE_COURIER = 'COURIER';
    public const SHIPPING_TYPE_PICKUP = 'PICKUP';

    /**
     * Номер телефона клиента (пример +71234567890)
     * Max length: 2048.
     */
    private ?string $billingPhone = null;

    /**
     * Идентификатор устройства клиента (device_id/gaid/idfa/ifv)
     * Max length: 2048.
     */
    private ?string $deviceId = null;

    /**
     * Адрес доставки
     * Max length: 2048.
     */
    private ?string $shippingAddress = null;

    /**
     * Номер телефона получателя (пример +71234567890)
     * Max length: 2048.
     */
    private ?string $shippingPhone = null;

    /**
     * Способ получения заказа
     * Enum: COURIER, PICKUP.
     */
    private ?string $shippingType = null;

    public function getBillingPhone(): ?string
    {
        return $this->billingPhone;
    }

    public function setBillingPhone(?string $billingPhone): self
    {
        $this->billingPhone = $billingPhone;

        return $this;
    }

    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    public function setDeviceId(?string $deviceId): self
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    public function getShippingAddress(): ?string
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(?string $shippingAddress): self
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    public function getShippingPhone(): ?string
    {
        return $this->shippingPhone;
    }

    public function setShippingPhone(?string $shippingPhone): self
    {
        $this->shippingPhone = $shippingPhone;

        return $this;
    }

    public function getShippingType(): ?string
    {
        return $this->shippingType;
    }

    public function setShippingType(?string $shippingType): self
    {
        $this->shippingType = $shippingType;

        return $this;
    }
}
