<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Repository\Category;

class CategoryForChoice
{
    private string $label;
    private string $value;

    public function __construct(string $label, string $value)
    {
        $this->label = $label;
        $this->value = $value;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
