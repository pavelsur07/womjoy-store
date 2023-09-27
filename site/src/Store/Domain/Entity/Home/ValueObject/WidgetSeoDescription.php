<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Home\ValueObject;

class WidgetSeoDescription
{
    private bool $isActive = false;
    private string $href = '';

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function setHref(string $href): void
    {
        $this->href = $href;
    }
}
