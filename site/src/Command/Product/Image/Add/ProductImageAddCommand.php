<?php

namespace App\Command\Product\Image\Add;

class ProductImageAddCommand
{
    public function __construct(
        private int $productId,
        /**
         * @var File[]
         */
        private array $files,

    )
    {
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }


}