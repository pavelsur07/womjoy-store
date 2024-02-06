<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Order\ValueObject;

use App\Store\Domain\Exception\StoreOrderException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class OrderMoysklad
{
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $id = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $created = false;

    public function isCreated(): bool
    {
        return $this->created === true;
    }

    public function created(): void
    {
        if ($this->created) {
            throw new StoreOrderException('The order has already been created');
        }

        $this->created = true;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }
}
