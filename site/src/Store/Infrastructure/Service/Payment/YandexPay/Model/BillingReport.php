<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Model;

class BillingReport extends AbstractObject
{
    /**
     * Идентификатор точки продаж
     * Default: null
     * Max length: 2048.
     */
    private ?string $branchId = null;

    /**
     * Идентификатор менеджера
     * Default: null
     * Max length: 2048.
     */
    private ?string $managerId = null;

    public function getBranchId(): ?string
    {
        return $this->branchId;
    }

    public function setBranchId(?string $branchId): self
    {
        $this->branchId = $branchId;

        return $this;
    }

    public function getManagerId(): ?string
    {
        return $this->managerId;
    }

    public function setManagerId(?string $managerId): self
    {
        $this->managerId = $managerId;

        return $this;
    }
}
