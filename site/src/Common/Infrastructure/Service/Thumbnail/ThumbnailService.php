<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Thumbnail;

use Gumlet\ImageResize;
use Gumlet\ImageResizeException;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;

class ThumbnailService
{
    public const JPG = 1;
    public const WEBP = 2;

    private const QUALITY = 90;

    public function __construct(
        private readonly FilesystemOperator $storage,
        private readonly string $baseUrl
    ) {
    }

    /**
     * @throws FilesystemException
     * @throws ImageResizeException
     */
    public function convertImagePngToJpeg(string $path, string $name): File
    {
        $fullName = $path . '/' . $name;
        $basePatch = $path;

        $tmp = tmpfile();
        $file = $this->storage->read($fullName);

        fwrite($tmp, $file);

        $image = new ImageResize(stream_get_meta_data($tmp)['uri']);
        $stream = fopen('php://memory', 'wrb+');

        $name = explode('.', $name)[0] . '.jpg';

        $image->save($stream, IMAGETYPE_JPEG, 100);
        $this->storage->writeStream($path . '/' . $name, $stream);

        fclose($stream);
        fclose($tmp);

        $fileSize = $this->storage->fileSize($path . '/' . $name);
        return new File(
            path: $path,
            name: $name,
            size: $fileSize
        );
    }

    public function createThumbnails(string $path, string $inputName, string $outputName): void
    {
    }
}
