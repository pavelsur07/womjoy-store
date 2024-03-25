<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Request;

use App\Store\Infrastructure\Service\Payment\YandexPay\Model\AbstractObject;

class CreateOrderResponse extends AbstractObject
{
    private ?string $paymentUrl = null;

    public function getPaymentUrl(): ?string
    {
        return $this->paymentUrl;
    }

    public function setPaymentUrl(?string $paymentUrl): self
    {
        $this->paymentUrl = $paymentUrl;

        return $this;
    }
}
