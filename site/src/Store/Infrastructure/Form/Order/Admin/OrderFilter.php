<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Form\Order\Admin;

class OrderFilter
{
    public ?string $name = null;
    public ?string $status = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }
}
