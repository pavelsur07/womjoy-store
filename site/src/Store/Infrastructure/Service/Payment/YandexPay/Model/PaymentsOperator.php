<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Model;

class PaymentsOperator extends AbstractObject
{
    /**
     * Max length: 2048.
     */
    private ?array $phones = null;

    public function getPhones(): ?array
    {
        return $this->phones;
    }

    public function setPhones(?array $phones): self
    {
        $this->phones = $phones;

        return $this;
    }
}
