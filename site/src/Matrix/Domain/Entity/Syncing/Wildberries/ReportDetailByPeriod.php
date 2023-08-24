<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\Syncing\Wildberries;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/*#[ORM\Entity]
#[ORM\Table(name: '`matrix_syncing_report_details`')]*/
class ReportDetailByPeriod
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'integer')]
    private int $keyId;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $realizationreportId = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $dateFrom = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $dateTo = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $createDt = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $rrdId = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $rawData = null;

    public function __construct(
        int $keyId,
        ?string $realizationreportId,
        ?DateTimeImmutable $dateFrom,
        ?DateTimeImmutable $dateTo,
        ?DateTimeImmutable $createDt,
        ?string $rrdId,
        ?array $rawData
    ) {
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

    public function getRawData(): ?array
    {
        return $this->rawData;
    }

    public function getRealizationreportId(): ?string
    {
        return $this->realizationreportId;
    }

    public function getRrdId(): ?string
    {
        return $this->rrdId;
    }
}
