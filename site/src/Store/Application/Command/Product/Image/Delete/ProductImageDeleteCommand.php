<?php

declare(strict_types=1);

namespace App\Store\Application\Command\Product\Image\Delete;

final class ProductImageDeleteCommand
{
    public function __construct(
        private readonly int $imageId,
    ) {
    }

    public function getImageId(): int
    {
        return $this->imageId;
    }
}
