<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Common\Infrastructure\Uploader\FileUploader;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StoragePathExtension extends AbstractExtension
{
    public function __construct(private readonly FileUploader $uploader)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('storage_path', [$this, 'path'], ['is_safe' => ['html']]),
        ];
    }

    public function path(string $path): string
    {
        return $this->uploader->generateUrl($path);
    }
}
