<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product\ValueObject;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class ProductPrice
{
    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $price = 0;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $listPrice = 0;

    public function __construct(int $price = 0)
    {
        $this->price = $price;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function getListPrice(): int
    {
        if ($this->listPrice === 0) {
            return $this->price;
        }
        return $this->listPrice;
    }

    public function setListPrice(int $listPrice): void
    {
        $this->listPrice = $listPrice;
    }
}
