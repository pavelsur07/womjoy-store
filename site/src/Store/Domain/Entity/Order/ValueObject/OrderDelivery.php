<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Order\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class OrderDelivery
{
    #[ORM\Column(nullable: true)]
    private ?string $address = null;

    #[ORM\Column(nullable: true)]
    private ?string $index = null;

    #[ORM\Column(nullable: true)]
    private ?string $externalDeliveryNumber = null;

    public function __construct(?string $address, ?string $index = null)
    {
        $this->address = $address;
        $this->index = $index;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getIndex(): ?string
    {
        return $this->index;
    }

    public function getExternalDeliveryNumber(): ?string
    {
        return $this->externalDeliveryNumber;
    }

    public function setExternalDeliveryNumber(?string $externalDeliveryNumber): void
    {
        $this->externalDeliveryNumber = $externalDeliveryNumber;
    }
}
