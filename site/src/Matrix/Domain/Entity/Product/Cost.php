<?php

namespace App\Matrix\Domain\Entity\Product;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`matrix_product_costs`')]
class Cost
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;
    #[ORM\Column(type: 'datetime')]
    private DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: Product::class,inversedBy: 'costs')]
    private Product $product;
    #[ORM\Column(type: 'integer')]
    private int $value = 0;
    #[ORM\Column(type: 'string')]
    private string $currency = 'RUB';

    /**
     * @param DateTimeImmutable $createdAt
     * @param Product           $product
     * @param int               $value
     * @param string            $currency
     */
    public function __construct(DateTimeImmutable $createdAt, Product $product, int $value, string $currency = 'RUB')
    {
        $this->createdAt = $createdAt;
        $this->product = $product;
        $this->value = $value;
        $this->currency = $currency;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}