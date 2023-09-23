<?php

declare(strict_types=1);

namespace App\Store\Application\Command\Product\Image\Add;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Infrastructure\Repository\ProductRepository;

final readonly class ProductImageHandler
{
    public function __construct(
        private ProductRepository $products,
        private Flusher $flusher,
    ) {
    }

    public function __invoke(ProductImageAddCommand $command): void
    {
        $product = $this->products->get($command->getProductId());
        foreach ($command->getFiles() as $file) {
            $product->addImage(
                $file->getPath(),
                $file->getName(),
                $file->getSize()
            );

            $this->flusher->flush();
        }
    }
}
