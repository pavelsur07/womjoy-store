<?php

declare(strict_types=1);

namespace App\Store\Application\Command\Product\Image\Add;

class ProductImageAddCommand
{
    public function __construct(
        private int $productId,
        /**
         * @var File[]
         */
        private array $files,
    ) {}

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getFiles(): array
    {
        return $this->files;
    }
}
