<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`store_product_review`')]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    private Product $product;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column]
    private int $vote;

    #[ORM\Column(type: 'text', length: 2000)]
    private string $text;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private string|null $customerName = null;

    #[ORM\Column]
    private bool $active = false;

    public function __construct(Product $product, DateTimeImmutable $createdAt, int $vote, string $text, ?string $customerName)
    {
        $this->product = $product;
        $this->createdAt = $createdAt;
        $this->vote = $vote;
        $this->text = $text;
        $this->customerName = $customerName;
    }

    public function setVote(int $vote): void
    {
        $this->vote = $vote;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function setCustomerName(?string $customerName): void
    {
        $this->customerName = $customerName;
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

    public function getVote(): int
    {
        return $this->vote;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
