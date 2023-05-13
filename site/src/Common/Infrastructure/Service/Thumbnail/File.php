<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Thumbnail;

final class File
{
    public function __construct(
        private readonly string $path,
        private readonly string $name,
        private readonly int $size,
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
