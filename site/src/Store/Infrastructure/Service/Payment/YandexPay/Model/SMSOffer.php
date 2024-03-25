<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Model;

class SMSOffer extends AbstractObject
{
    /**
     * Номер телефона клиента (пример +71234567890)
     * Max length: 2048.
     */
    private string $phone;

    public function __construct(string $phone)
    {
        $this->phone = $phone;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}
