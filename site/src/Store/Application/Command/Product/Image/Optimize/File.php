<?php

declare(strict_types=1);

namespace App\Store\Application\Command\Product\Image\Optimize;

class File
{
    public function __construct(
        public string $path,
        public string $name,
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
}
