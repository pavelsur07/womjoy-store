<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Home\ValueObject;

class WidgetSeoDescription
{
    private bool $isActive = false;
    private string $description = '';

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
