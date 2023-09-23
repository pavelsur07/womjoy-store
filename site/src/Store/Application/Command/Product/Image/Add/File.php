<?php

declare(strict_types=1);

namespace App\Store\Application\Command\Product\Image\Add;

final readonly class File
{
    public function __construct(
        private string $path,
        private string $name,
        private int $size,
    ) {
    }

    public function getPath(): string
    {
        return $this->path;
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
