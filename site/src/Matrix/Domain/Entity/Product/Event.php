<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\Product;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`matrix_product_events`')]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'events')]
    private Product $product;
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $dataStartAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private DateTimeImmutable|null $dataFinishAt = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private string|null $note = null;

    public function __construct(
        Product $product,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $dataStartAt,
        ?string $note = null,
        ?DateTimeImmutable $dataFinishAt = null
    ) {
        $this->product = $product;
        $this->createdAt = $createdAt;
        $this->dataStartAt = $dataStartAt;
        $this->dataFinishAt = $dataFinishAt;
        $this->note = $note;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getDataStartAt(): DateTimeImmutable
    {
        return $this->dataStartAt;
    }

    public function getDataFinishAt(): ?DateTimeImmutable
    {
        return $this->dataFinishAt;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }
}
