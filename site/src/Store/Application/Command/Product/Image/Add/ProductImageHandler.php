<?php

declare(strict_types=1);

namespace App\Store\Application\Command\Product\Image\Add;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Product\Image;
use App\Store\Infrastructure\Repository\ImageRepository;
use App\Store\Infrastructure\Repository\ProductRepository;

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
            $this->flusher->flush();
        }
    }
}
