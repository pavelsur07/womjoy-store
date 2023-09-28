<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Home\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class ProductSeoDescription
{
    #[ORM\Column(type: 'boolean', options: ['default'=>false])]
    private bool $isActive = false;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
