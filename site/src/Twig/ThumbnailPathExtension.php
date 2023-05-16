<?php

declare(strict_types=1);

namespace App\Twig;

use App\Common\Infrastructure\Service\Thumbnail\ThumbnailService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ThumbnailPathExtension extends AbstractExtension
{
    public function __construct(
        private readonly ThumbnailService $thumbnail
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('thumbnail_path', [$this, 'path'], ['is_safe' => ['html']]),
        ];
    }

    public function path(string $path, string $file, int $width = 0, int $height = 0): string
    {
        return $this->thumbnail->generateUrl($path, $file, $width, $height);
    }
}
