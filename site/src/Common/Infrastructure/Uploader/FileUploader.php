<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Uploader;

use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private FilesystemOperator $storage;
    private string $basUrl;

    public function __construct(FilesystemOperator $defaultStorage, string $basUrl)
    {
        $this->storage = $defaultStorage;
        $this->basUrl = $basUrl;
    }

    /**
     * @throws FilesystemException
     */
    public function upload(UploadedFile $file, string $path = null): File
    {
        if ($path === null) {
            $path = date('Y/m/d');
        }

        $name = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();

        $this->storage->createDirectory($path);
        $stream = fopen($file->getRealPath(), 'rb+');
        $this->storage->writeStream($path . '/' . $name, $stream);
        fclose($stream);

        return new File($path, $name, $file->getSize());
    }

    /**
     * @throws FilesystemException
     */
    public function uploadByUrl(string $url, string $path, string $extension): ?File
    {
        $content = file_get_contents($url);

        if ($content) {
            $fileName = FilenameGenerator::generate($path, $extension);
            $baseName = $fileName . '.' . $extension;

            $orig = $path . '/' . $baseName;

            $f = fopen($orig, 'wb');
            $this->storage->writeStream($path . '/' . $fileName, $f);
            fclose($f);

            return new File($path, $fileName, 0 /* $file->getSize() */);
        }
        return null;
    }

    /**
     * @throws FilesystemException
     */
    public function write(string $content, string $file, ?string $patch = null): void
    {
        if ($patch !== null) {
            $location = $patch . '/' . $file;
            $this->storage->createDirectory($location);
        } else {
            $location = $file;
        }

        $this->storage->write($location, $content);
    }

    public function generateUrl(string $path): string
    {
        return $this->basUrl . '/' . $path;
    }

    /**
     * @throws FilesystemException
     */
    public function remove(string $path, string $name): void
    {
        $this->storage->delete($path . '/' . $name);
    }
}
