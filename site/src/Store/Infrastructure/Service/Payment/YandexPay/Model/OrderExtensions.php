<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Model;

class OrderExtensions extends AbstractObject
{
    /**
     * Дополнительные параметры для отчета сплита.
     */
    private ?BillingReport $billingReport = null;

    /**
     * Дополнительные параметры для отправки ссылки на оплату с использованием QR.
     */
    private ?QRData $qrData = null;

    /**
     * Дополнительные параметры для отправки ссылки на оплату с использованием SMS.
     */
    private ?SMSOffer $smsOffer = null;

    public function getBillingReport(): ?BillingReport
    {
        return $this->billingReport;
    }

    public function setBillingReport(?BillingReport $billingReport): self
    {
        $this->billingReport = $billingReport;

        return $this;
    }

    public function getQrData(): ?QRData
    {
        return $this->qrData;
    }

    public function setQrData(?QRData $qrData): self
    {
        $this->qrData = $qrData;

        return $this;
    }

    public function getSmsOffer(): ?SMSOffer
    {
        return $this->smsOffer;
    }

    public function setSmsOffer(?SMSOffer $smsOffer): self
    {
        $this->smsOffer = $smsOffer;

        return $this;
    }
}
