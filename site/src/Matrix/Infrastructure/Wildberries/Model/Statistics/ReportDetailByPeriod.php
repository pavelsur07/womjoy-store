<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Wildberries\Model\Statistics;

use DateTimeImmutable;

class ReportDetailByPeriod
{
    private int $keyId;
    private ?string $realizationreport_id = null; // ": 1234567,

    private ?DateTimeImmutable $date_from = null; // : "2022-10-17T00:00:00Z",

    private ?DateTimeImmutable $date_to = null; // ": "2022-10-23T00:00:00Z",

    private ?DateTimeImmutable $create_dt = null;  // ": "2022-10-24T14:40:32",

    private ?string $rrd_id = null; // ": 1232610467,

    private array $rawData;

    public function getKeyId(): int
    {
        return $this->keyId;
    }

    public function setKeyId(int $keyId): void
    {
        $this->keyId = $keyId;
    }

    public function getRealizationreportId(): ?string
    {
        return $this->realizationreport_id;
    }

    public function setRealizationreportId(?string $realizationreport_id): void
    {
        $this->realizationreport_id = $realizationreport_id;
    }

    public function getDateFrom(): ?DateTimeImmutable
    {
        return $this->date_from;
    }

    public function setDateFrom(?DateTimeImmutable $date_from): void
    {
        $this->date_from = $date_from;
    }

    public function getDateTo(): ?DateTimeImmutable
    {
        return $this->date_to;
    }

    public function setDateTo(?DateTimeImmutable $date_to): void
    {
        $this->date_to = $date_to;
    }

    public function getCreateDt(): ?DateTimeImmutable
    {
        return $this->create_dt;
    }

    public function setCreateDt(?DateTimeImmutable $create_dt): void
    {
        $this->create_dt = $create_dt;
    }

    public function getRrdId(): ?string
    {
        return $this->rrd_id;
    }

    public function setRrdId(?string $rrd_id): void
    {
        $this->rrd_id = $rrd_id;
    }

    public function getRawData(): array
    {
        return $this->rawData;
    }

    public function setRawData(array $rawData): void
    {
        $this->rawData = $rawData;
    }
}
