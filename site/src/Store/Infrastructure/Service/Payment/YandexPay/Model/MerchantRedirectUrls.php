<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Model;

class MerchantRedirectUrls extends AbstractObject
{
    /**
     * Ссылка для переадресации пользователя в случае отмены процесса оплаты
     * Max length: 2048.
     */
    private ?string $onAbort = null;

    /**
     * Ссылка для переадресации пользователя в случае возникновения ошибки во время оплаты, или если срок ссылки на оплату истек
     * Max length: 2048.
     */
    private ?string $onError = null;

    /**
     * Ссылка для переадресации пользователя в случае успешной оплаты
     * Max length: 2048.
     */
    private ?string $onSuccess = null;

    public function getOnAbort(): ?string
    {
        return $this->onAbort;
    }

    public function setOnAbort(?string $onAbort): self
    {
        $this->onAbort = $onAbort;

        return $this;
    }

    public function getOnError(): ?string
    {
        return $this->onError;
    }

    public function setOnError(?string $onError): self
    {
        $this->onError = $onError;

        return $this;
    }

    public function getOnSuccess(): ?string
    {
        return $this->onSuccess;
    }

    public function setOnSuccess(?string $onSuccess): self
    {
        $this->onSuccess = $onSuccess;

        return $this;
    }
}
