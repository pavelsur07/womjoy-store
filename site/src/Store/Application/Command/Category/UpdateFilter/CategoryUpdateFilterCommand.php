<?php

declare(strict_types=1);

namespace App\Store\Application\Command\Category\UpdateFilter;

final readonly class CategoryUpdateFilterCommand
{
    public function __construct(
        private int $categoryId,
    ) {}

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }
}
