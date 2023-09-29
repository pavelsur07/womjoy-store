<?php

declare(strict_types=1);

namespace App\Store\Application\Command\Product\Image\Optimize;

readonly class ProductImageOptimizeCommand
{
    public function __construct(
        private int $productId,
    ) {}

    public function getProductId(): int
    {
        return $this->productId;
    }
}
