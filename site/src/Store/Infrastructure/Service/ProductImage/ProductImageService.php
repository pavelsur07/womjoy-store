<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\ProductImage;

use App\Common\Infrastructure\Service\Thumbnail\ThumbnailService;
use App\Store\Domain\Exception\StoreProductImageException;
use Gumlet\ImageResizeException;
use League\Flysystem\FilesystemException;

readonly class ProductImageService
{
    public const THUMBNAILS = [
        [1170, 1560],
        [900, 1200],
        [390, 520],
        [300, 400],
    ];

    private array $thumbnailsTemplate;

    public function __construct(
        private ThumbnailService $thumbnails,
        private string $cachePathImages,
    ) {
        $this->thumbnailsTemplate = self::THUMBNAILS;
    }

    /**
     * @throws FilesystemException
     * @throws ImageResizeException
     */
    public function checkExtension(string $path, string $name): File|null
    {
        $extension = explode('.', $name)[1];
        if ($extension !== 'jpg') {
            $file = $this->thumbnails->convertImagePngToJpeg(
                path: $path,
                name: $name
            );

            $this->thumbnails->remove(path: $path, name: $name);

            return new File(path: $file->getPath(), name: $file->getName(), size: $file->getSize());
        }

        return null;
    }

    public function optimize(string $path, string $name): void
    {
        try {
            foreach ($this->thumbnailsTemplate as $thumbnail) {
                $outputPath = $path . $this->getCachePatch($thumbnail[0], $thumbnail[1]);
                $this->thumbnails->createThumbnail(
                    path: $path,
                    inputName: $name,
                    outputPath: $outputPath,
                    width: $thumbnail[0],
                    height: $thumbnail[1],
                );
            }

            foreach ($this->thumbnailsTemplate as $thumbnail) {
                $outputPath = $path . $this->getCachePatch($thumbnail[0], $thumbnail[1]);
                $this->thumbnails->createThumbnail(
                    path: $path,
                    inputName: $name,
                    outputPath: $outputPath,
                    width: $thumbnail[0],
                    height: $thumbnail[1],
                    type: ThumbnailService::WEBP,
                );
            }
        } catch (ImageResizeException $e) {
            throw new StoreProductImageException($e->getMessage());
        } catch (FilesystemException $e) {
            throw new StoreProductImageException($e->getMessage());
        }
    }

    public function remove(string $path, string $name): void
    {
    }

    private function getCachePatch($width = 0, int $height = 0): string
    {
        return $this->cachePathImages . '/' . $width . '-' . $height . '/';
    }
}
