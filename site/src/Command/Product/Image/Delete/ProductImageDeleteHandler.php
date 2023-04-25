<?php

declare(strict_types=1);

namespace App\Command\Product\Image\Delete;

use App\Repository\Flusher;
use App\Repository\ImageRepository;
use App\Service\Uploader\FileUploader;
use League\Flysystem\FilesystemException;

final readonly class ProductImageDeleteHandler
{
    public function __construct(
        private ImageRepository $images,
        private Flusher $flusher,
        private FileUploader $uploader,
    ) {
    }

    /**
     * @throws FilesystemException
     */
    public function __invoke(ProductImageDeleteCommand $command): void
    {
        $image = $this->images->get($command->getImageId());
        $product = $image->getProduct();

        $product->removeImage($image);
        $this->uploader->remove($image->getPatch(), $image->getName());
        $this->flusher->flush();
    }
}
