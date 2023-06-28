<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Order\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class DeliveryData
{
    #[ORM\Column]
    private string $index;

    #[ORM\Column]
    private string $address;

    public function __construct($index, $address)
    {
        $this->index = $index;
        $this->address = $address;
    }

    public function getIndex(): string
    {
        return $this->index;
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}
