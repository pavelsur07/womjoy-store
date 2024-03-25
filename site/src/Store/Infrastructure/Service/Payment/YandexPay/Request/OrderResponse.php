<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Request;

use App\Store\Infrastructure\Service\Payment\YandexPay\Model\AbstractObject;

class OrderResponse extends AbstractObject
{
    private ?array $order;

    public function getOrder(): ?array
    {
        return $this->order;
    }

    public function setOrder(?array $order): self
    {
        $this->order = $order;

        return $this;
    }
}
