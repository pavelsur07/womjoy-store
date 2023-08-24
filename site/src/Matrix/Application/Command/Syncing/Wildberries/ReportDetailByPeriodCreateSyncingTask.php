<?php

declare(strict_types=1);

namespace App\Matrix\Application\Command\Syncing\Wildberries;

use DateTimeImmutable;

final readonly class ReportDetailByPeriodCreateSyncingTask
{
    public function __construct(
        private int $keyId,
        private string $keyValue,
        private DateTimeImmutable $dateFrom,
        private DateTimeImmutable $dateTo,
    ) {
    }

    public function getKeyId(): int
    {
        return $this->keyId;
    }

    public function getKeyValue(): string
    {
        return $this->keyValue;
    }

    public function getDateFrom(): DateTimeImmutable
    {
        return $this->dateFrom;
    }

    public function getDateTo(): DateTimeImmutable
    {
        return $this->dateTo;
    }
}
