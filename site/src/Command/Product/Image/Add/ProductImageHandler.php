<?php

declare(strict_types=1);

namespace App\Command\Product\Image\Add;

use App\Entity\Image;
use App\Repository\Flusher;
use App\Repository\ImageRepository;
use App\Repository\ProductRepository;

final readonly class ProductImageHandler
{
    public function __construct(
        private ProductRepository $products,
        private ImageRepository $images,
        private Flusher $flusher,
    ) {
    }

    public function __invoke(ProductImageAddCommand $command): void
    {
        $product = $this->products->get($command->getProductId());
        foreach ($command->getFiles() as $file) {
            $product->addImage(
                $image = new Image(
                    product: $product,
                    patch: $file->getPatch(),
                    name: $file->getName(),
                    size: $file->getSize(),
                    sort: \count($product->getImages())
                )
            );

            $this->images->save($image);
            $this->products->flush();
        }
    }
}
