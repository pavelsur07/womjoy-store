<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Imgproxy;

use App\Common\Infrastructure\Service\Imgproxy\Enum\Gravity;
use App\Common\Infrastructure\Service\Imgproxy\Enum\ResizingType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class UrlBuilder
{
    public function __construct(
        #[Autowire('%env(IMGPROXY_BASE_URL)%')]
        private string $baseUrl,
        private ?Signature $signature = null,
    ) {}

    public function build(
        string $url,
        int $width,
        int $height,
        ResizingType $resizingType,
        Gravity $gravity,
        bool $enlarge = false,
        string $extension = null
    ): Url {
        return (new Url($this, $url, $width, $height))
            ->setResizingType($resizingType)
            ->setGravity($gravity)
            ->setEnlarge($enlarge)
            ->setExtension($extension);
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getSignature(): ?Signature
    {
        return $this->signature;
    }
}
