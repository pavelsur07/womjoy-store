<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product\ValueObject;

use App\Store\Domain\Exception\StoreProductException;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class ProductPrice
{
    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $price = 0;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $listPrice = 0;

    private string $currency = 'RUB';

    private string $currencySymbol = 'р.';

    public function __construct(int $price = 0)
    {
        $this->price = $price;
    }

    public function changePrice(int $oldPrice, ?int $listPrice = null): void
    {
        if ($oldPrice < $listPrice) {
            throw new StoreProductException('Error old price < list price.');
        }
        $this->listPrice = $listPrice === null ? $oldPrice : $listPrice;
        $this->price = $oldPrice;
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

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getCurrencySymbol(): string
    {
        return $this->currencySymbol;
    }
}
