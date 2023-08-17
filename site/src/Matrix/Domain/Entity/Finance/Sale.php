<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\Finance;

use App\Matrix\Domain\Entity\Finance\ValueObject\Service;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`matrix_finance_sales`')]
class Sale
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $saleAt;

    #[ORM\Embedded(class: Service::class, columnPrefix: 'service_')]
    private Service $service;

    #[ORM\Column]
    private string $productIdentity;

    #[ORM\Column]
    private int $sale;

    #[ORM\Column]
    private int $cost;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $transactionId = null;

    public function __construct(
        DateTimeImmutable $createdAt,
        DateTimeImmutable $saleAt,
        Service $service,
        string $productIdentity,
        int $sale,
        int $cost,
        ?string $transactionId = null
    ) {
        $this->createdAt = $createdAt;
        $this->updatedAt = $createdAt;
        $this->saleAt = $saleAt;
        $this->service = $service;
        $this->productIdentity = $productIdentity;
        $this->sale = $sale;
        $this->cost = $cost;
        $this->transactionId = $transactionId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getSaleAt(): DateTimeImmutable
    {
        return $this->saleAt;
    }

    public function getService(): Service
    {
        return $this->service;
    }

    public function getProductIdentity(): string
    {
        return $this->productIdentity;
    }

    public function getSale(): int
    {
        return $this->sale;
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }
}
