<?php

declare(strict_types=1);

namespace App\Store\Application\Command\Product\Image\Optimize;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Infrastructure\Repository\ProductRepository;
use App\Store\Infrastructure\Service\ProductImage\ProductImageService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

final readonly class ProductImageOptimizeHandler
{
    public function __construct(
        private ProductRepository $products,
        private ProductImageService $service,
        private Flusher $flusher,
    ) {}

    #[AsMessageHandler]
    public function __invoke(ProductImageOptimizeCommand $command): void
    {
        $product = $this->products->get($command->getProductId());

        foreach ($product->getImages() as $image) {
            $file = $this->service->checkExtension($image->getPath(), $image->getName());
            if ($file !== null) {
                $image->setName($file->getName());
            }
        }
        $this->flusher->flush();

        foreach ($product->getImages() as $image) {
            if (!$image->isOptimize()) {
                $this->service->optimize(path: $image->getPath(), name: $image->getName());
                $image->optimize();
            }
        }
        $this->flusher->flush();
    }
}
