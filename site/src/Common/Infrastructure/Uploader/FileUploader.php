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
