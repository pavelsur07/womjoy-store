<?php

declare(strict_types=1);

namespace App\Store\Application\Command\Product\Image\Optimize;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Matrix\Domain\Repository\Product\ProductRepositoryInterface;
use App\Store\Infrastructure\Service\ProductImage\ProductImageService;

final readonly class ProductImageOptimizeHandler
{
    public function __construct(
        private ProductRepositoryInterface $products,
        private ProductImageService $service,
        private Flusher $flusher,
    ) {
    }
}
