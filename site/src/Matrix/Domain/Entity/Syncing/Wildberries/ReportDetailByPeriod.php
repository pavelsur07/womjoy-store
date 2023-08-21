<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\Syncing\Wildberries;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`matrix_syncing_report_details`')]
class ReportDetailByPeriod
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'integer')]
    private int $keyId;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $realizationreportId = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $dateFrom = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $dateTo = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $createDt = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $rrdId = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $rawData = null;

    public function __construct(int $keyId, ?int $realizationreportId, ?DateTimeImmutable $dateFrom, ?DateTimeImmutable $dateTo, ?DateTimeImmutable $createDt, ?int $rrdId, ?array $rawData)
    {
        $this->keyId = $keyId;
        $this->realizationreportId = $realizationreportId;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->createDt = $createDt;
        $this->rrdId = $rrdId;
        $this->rawData = $rawData;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getKeyId(): int
    {
        return $this->keyId;
    }

    public function getRealizationreportId(): ?int
    {
        return $this->realizationreportId;
    }

    public function getDateFrom(): ?DateTimeImmutable
    {
        return $this->dateFrom;
    }

    public function getDateTo(): ?DateTimeImmutable
    {
        return $this->dateTo;
    }

    public function getCreateDt(): ?DateTimeImmutable
    {
        return $this->createDt;
    }

    public function getRrdId(): ?int
    {
        return $this->rrdId;
    }

    public function getRawData(): ?array
    {
        return $this->rawData;
    }
}
