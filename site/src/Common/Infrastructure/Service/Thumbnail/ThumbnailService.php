<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Thumbnail;

use Gumlet\ImageResize;
use Gumlet\ImageResizeException;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use League\Glide\ServerFactory;

class ThumbnailService
{
    public const JPG = 1;
    public const WEBP = 2;
    private const QUALITY = 100;

    public function __construct(
        private readonly FilesystemOperator $defaultStorage,
        private readonly string $baseUrl,
        private readonly string $cachePathImages,
    ) {}

    /**
     * @throws FilesystemException
     * @throws ImageResizeException
     */
    public function convertImagePngToJpeg(string $path, string $name): File
    {
        $serverLeague = ServerFactory::create([
            'source' => $this->defaultStorage,
            'cache' => $this->defaultStorage,
        ]);

        $fullName = $path . '/' . $name;

        $tmp = tmpfile();
        $file = $this->defaultStorage->read($fullName);

        fwrite($tmp, $file);

        $image = new ImageResize(stream_get_meta_data($tmp)['uri']);
        $stream = fopen('php://memory', 'wrb+');

        $name = explode('.', $name)[0] . '.jpg';

        $image->save($stream, IMAGETYPE_JPEG, 100);
        $this->defaultStorage->writeStream($path . '/' . $name, $stream);

        fclose($stream);
        fclose($tmp);

        $fileSize = $this->defaultStorage->fileSize($path . '/' . $name);
        return new File(
            path: $path,
            name: $name,
            size: $fileSize
        );
    }

    /**
     * @throws FilesystemException
     * @throws ImageResizeException
     */
    public function createThumbnail(
        string $path,
        string $inputName,
        string $outputPath,
        int $width = 0,
        int $height = 0,
        int $type = self::JPG,
    ): File {
        $fullName = $path . '/' . $inputName;

        $tmp = tmpfile();
        $file = $this->defaultStorage->read($fullName);
        fwrite($tmp, $file);

        $image = new ImageResize(stream_get_meta_data($tmp)['uri']);
        // $image->resize($width, $height, true);
        $image->crop($width, $height);
        $stream = fopen('php://memory', 'wrb+');

        $name = explode('.', $inputName)[0] . '.jpg';

        switch ($type) {
            case self::JPG:
                $image->save($stream, IMAGETYPE_JPEG, self::QUALITY);
                $this->defaultStorage->createDirectory($outputPath);
                $this->defaultStorage->writeStream($outputPath . '/' . $name, $stream);
                break;
            case self::WEBP:
                $image->save($stream, IMAGETYPE_WEBP, self::QUALITY);
                $this->defaultStorage->createDirectory($outputPath);
                $this->defaultStorage->writeStream($outputPath . '/' . $name . '.webp', $stream);
        }

        fclose($stream);
        fclose($tmp);

        $fileSize = $this->defaultStorage->fileSize($outputPath . '/' . $name);
        return new File(
            path: $path,
            name: $name,
            size: $fileSize
        );
    }

    /**
     * @throws FilesystemException
     * @throws ImageResizeException
     */
    public function createThumbnailImgproxy(
        string $path,
        string $inputName,
        string $outputPath,
        int $width = 0,
        int $height = 0,
        int $type = self::JPG,
    ): File {
        $fullName = $path . '/' . $inputName;

        $tmp = tmpfile();
        $file = $this->defaultStorage->read($fullName);
        fwrite($tmp, $file);

        $image = new ImageResize(stream_get_meta_data($tmp)['uri']);
        // $image->resize($width, $height, true);

        $image->crop($width, $height);
        $stream = fopen('php://memory', 'wrb+');

        $name = explode('.', $inputName)[0] . '.jpg';

        switch ($type) {
            case self::JPG:
                $image->save($stream, IMAGETYPE_JPEG, self::QUALITY);
                $this->defaultStorage->createDirectory($outputPath);
                $this->defaultStorage->writeStream($outputPath . '/' . $name, $stream);
                break;
            case self::WEBP:
                $image->save($stream, IMAGETYPE_WEBP, self::QUALITY);
                $this->defaultStorage->createDirectory($outputPath);
                $this->defaultStorage->writeStream($outputPath . '/' . $name . '.webp', $stream);
        }

        fclose($stream);
        fclose($tmp);

        $fileSize = $this->defaultStorage->fileSize($outputPath . '/' . $name);
        return new File(
            path: $path,
            name: $name,
            size: $fileSize
        );
    }

    public function sign(string $str, $salt, $key): string
    {
        return hash_hmac('sha256', $salt . $str, $key, true);
    }

    public function saveImageFromURL($imageUrl, $savePath): false|string
    {
        // Получаем содержимое изображения по URL
        $imageData = file_get_contents($imageUrl);

        if ($imageData !== false) {
            // Определяем расширение изображения на основе его MIME-типа
            $imageType = mime_content_type($imageUrl);
            $extension = image_type_to_extension(exif_imagetype($imageUrl));

            // Формируем путь для сохранения файла
            $filePath = $savePath . '/' . uniqid() . $extension;

            // Сохраняем изображение
            if (file_put_contents($filePath, $imageData)) {
                return $filePath; // Возвращаем путь сохраненного файла
            }
            return false; // В случае ошибки возвращаем false
        }
        return false; // В случае ошибки загрузки изображения возвращаем false
    }

    /**
     * @throws FilesystemException
     */
    public function remove(string $path, string $name): void
    {
        $this->defaultStorage->delete($path . '/' . $name);
    }

    public function generateUrl(string $path, string $file, int $width = 0, int $height = 0): string
    {
        return $this->baseUrl . '/' . $path . $this->getCachePatch($width, $height) . $file;
    }

    private function getCachePatch($width = 0, int $height = 0): string
    {
        return $this->cachePathImages . '/' . $width . '-' . $height . '/';
    }
}
