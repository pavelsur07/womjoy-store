<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\ProductImage;

class File
{
    public function __construct(
        public string $path,
        public string $name,
        public int $size,
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
