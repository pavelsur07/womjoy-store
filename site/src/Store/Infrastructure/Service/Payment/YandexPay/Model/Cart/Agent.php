<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Model\Cart;

use App\Store\Infrastructure\Service\Payment\YandexPay\Model\AbstractObject;
use App\Store\Infrastructure\Service\Payment\YandexPay\Model\PaymentsOperator;
use App\Store\Infrastructure\Service\Payment\YandexPay\Model\TransferOperator;

class Agent extends AbstractObject
{
    /**
     * Описание значений: Ссылка
     * Enum: 1, 2, 3, 4, 5, 6, 7
     *
     * @link https://pay.yandex.ru/ru/docs/custom/fns#agent-type
     */
    private int $agentType;

    /**
     * Max length: 2048
     */
    private ?string $operation = null;

    private ?PaymentsOperator $paymentsOperator = null;

    /**
     * Max length: 2048
     */
    private ?array $phones = null;

    private ?TransferOperator $transferOperator = null;

    /**
     * @param int $agentType
     */
    public function __construct(int $agentType)
    {
        $this->agentType = $agentType;
    }

    public function getAgentType(): int
    {
        return $this->agentType;
    }

    public function getOperation(): ?string
    {
        return $this->operation;
    }

    public function setOperation(?string $operation): self
    {
        $this->operation = $operation;

        return $this;
    }

    public function getPaymentsOperator(): ?PaymentsOperator
    {
        return $this->paymentsOperator;
    }

    public function setPaymentsOperator(?PaymentsOperator $paymentsOperator): self
    {
        $this->paymentsOperator = $paymentsOperator;

        return $this;
    }

    public function getPhones(): ?array
    {
        return $this->phones;
    }

    public function setPhones(?array $phones): self
    {
        $this->phones = $phones;

        return $this;
    }

    public function getTransferOperator(): ?TransferOperator
    {
        return $this->transferOperator;
    }

    public function setTransferOperator(?TransferOperator $transferOperator): self
    {
        $this->transferOperator = $transferOperator;

        return $this;
    }
}
