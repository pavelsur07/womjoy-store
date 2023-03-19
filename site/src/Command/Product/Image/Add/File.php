<?php

declare(strict_types=1);

namespace App\Command\Product\Image\Add;

final readonly class File
{
    public function __construct(
        private string $pach,
        private string $name,
        private int $size,
    ) {
    }

    public function getPatch(): string
    {
        return $this->pach;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
