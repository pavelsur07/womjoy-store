<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Imgproxy;

use App\Common\Infrastructure\Service\Imgproxy\Enum\Gravity;
use App\Common\Infrastructure\Service\Imgproxy\Enum\ProcessingOption;
use App\Common\Infrastructure\Service\Imgproxy\Enum\ResizingType;

class Url
{
    private OptionSet $options;
    private ?Gravity $gravity = null;
    private ?ResizingType $resizingType = null;

    private bool $enlarge = false;
    private ?string $extension = null;

    public function __construct(
        private readonly UrlBuilder $builder,
        private readonly string $url,
        private readonly int $width,
        private readonly int $height
    ) {
        $this->options = new OptionSet();
        $this->options->withWidth($this->width);
        $this->options->withHeight($this->height);
    }

    public function getUrl(bool $secure = false): string
    {
        $this->options->withResizingType($this->resizingType->value);
        $this->options->withGravity($this->gravity->value);

        $this->enlarge ? $this->options->withEnlarge() : $this->options->unset(
            ProcessingOption::ENLARGE->value
        );
        $this->extension ? $this->options->withFormat($this->extension) : $this->options->unset(
            ProcessingOption::FORMAT->value
        );

        $path = sprintf('/%s/%s', $this->options->toString(), rtrim(strtr(base64_encode($this->url), '+/', '-_'), '='));

        if ($secure && $this->builder->getSignature()) {
            $path = sprintf('/%s%s', $this->builder->getSignature()->sing($path), $path);
        } else {
            $path = sprintf('/insecure%s', $path);
        }

        return sprintf('%s%s.%s', $this->builder->getBaseUrl(), $path, ($this->extension ?: $this->resolveExtension()));
    }

    public function getOptions(): OptionSet
    {
        return $this->options;
    }

    public function getGravity(): ?Gravity
    {
        return $this->gravity;
    }

    public function setGravity(?Gravity $gravity): self
    {
        $this->gravity = $gravity;

        return $this;
    }

    public function getResizingType(): ?ResizingType
    {
        return $this->resizingType;
    }

    public function setResizingType(?ResizingType $resizingType): self
    {
        $this->resizingType = $resizingType;

        return $this;
    }

    public function isEnlarge(): bool
    {
        return $this->enlarge;
    }

    public function setEnlarge(bool $enlarge): self
    {
        $this->enlarge = $enlarge;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    protected function resolveExtension(): string
    {
        if (str_starts_with($this->url, "local://")) {
            $path = substr($this->url, 8);
        } else {
            $path = parse_url($this->url, PHP_URL_PATH);
        }

        $ext = $path ? strtolower(pathinfo($path, PATHINFO_EXTENSION)) : "";

        return $ext ?: '';
    }

    public function setExtension(?string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }
}
