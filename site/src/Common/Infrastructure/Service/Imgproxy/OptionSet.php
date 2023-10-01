<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Imgproxy;

use App\Common\Infrastructure\Service\Imgproxy\Enum\Gravity;
use App\Common\Infrastructure\Service\Imgproxy\Enum\ProcessingOption;
use App\Common\Infrastructure\Service\Imgproxy\Enum\ResizingType;
use InvalidArgumentException;

class OptionSet
{
    public const TRANSPARENT_BG = 'FF00FF';

    private array $options = [];

    public function unset(string $name): self
    {
        unset($this->options[$name]);

        return $this;
    }

    public function toString(): string
    {
        $parts = [];
        foreach ($this->options as $name => $args) {
            $nameAndArgs = array_merge([$name], $args);
            $parts[] = implode(':', $nameAndArgs); // not using [$name, ...$args] to keep it 7.2 compatible
        }

        return implode('/', $parts);
    }

    public function withWidth(int $w): self
    {
        if ($w < 0) {
            throw new InvalidArgumentException('width must be >= 0');
        }

        return $this->set(ProcessingOption::WIDTH->value, $w);
    }

    public function width(): ?int
    {
        return $this->firstValue(ProcessingOption::WIDTH->value, 'int');
    }

    public function withHeight(int $h): self
    {
        if ($h < 0) {
            throw new InvalidArgumentException('height must be >= 0');
        }

        return $this->set(ProcessingOption::HEIGHT->value, $h);
    }

    public function height(): ?int
    {
        return $this->firstValue(ProcessingOption::HEIGHT->value, 'int');
    }

    public function withResizingType(string $rt): self
    {
        return match ($rt) {
            ResizingType::Fit->value,
            ResizingType::Fill->value,
            ResizingType::FillDown->value,
            ResizingType::Force->value,
            ResizingType::Auto->value => $this->set(
                name: ProcessingOption::RESIZING_TYPE->value,
                args: $rt
            ),
            default => throw new InvalidArgumentException("unknown resizing type {$rt}"),
        };
    }

    public function resizingType(): ?string
    {
        return $this->firstValue(ProcessingOption::RESIZING_TYPE->value, 'string');
    }

    public function withDpr(int $v): self
    {
        if ($v <= 0) {
            throw new InvalidArgumentException('dpr must be greater than 0');
        }

        return $this->set(ProcessingOption::DPR->value, $v);
    }

    public function dpr(): ?int
    {
        return $this->firstValue(ProcessingOption::DPR->value, 'int');
    }

    public function withEnlarge(): self
    {
        return $this->set(ProcessingOption::ENLARGE->value, 1);
    }

    public function enlarge(): ?bool
    {
        return $this->firstValue(ProcessingOption::ENLARGE->value, 'bool');
    }

    public function withExtend(string $gravityType = null, $gravityX = null, $gravityY = null): self
    {
        if ($gravityType === Gravity::Smart->value) {
            throw new InvalidArgumentException('extend doesnt support smart gravity');
        }
        $gravity = $this->gravityOptions($gravityType, [], $gravityX, $gravityY);

        return $this->set(ProcessingOption::EXTEND->value, 1, ...$gravity);
    }

    public function extend(): ?array
    {
        return $this->get(ProcessingOption::EXTEND->value);
    }

    public function withGravity(string $type = null, $x = null, $y = null): self
    {
        $gravity = $this->gravityOptions($type, [], $x, $y);
        if (\count($gravity) === 0) {
            throw new InvalidArgumentException('no gravity type specified');
        }

        return $this->set(ProcessingOption::GRAVITY->value, ...$gravity);
    }

    public function gravity(): ?array
    {
        return $this->get(ProcessingOption::GRAVITY->value);
    }

    public function withCrop($w, $h, string $gravityType = null, $gravityX = null, $gravityY = null): self
    {
        $gravity = $this->gravityOptions($gravityType, [], $gravityX, $gravityY);

        return $this->set(ProcessingOption::CROP->value, $w, $h, ...$gravity);
    }

    public function crop(): ?array
    {
        return $this->get(ProcessingOption::CROP->value);
    }

    public function withPadding(int $t, int $r, int $b, int $l): self
    {
        if ($t < 0) {
            throw new InvalidArgumentException('top padding must be >= 0');
        }
        if ($r < 0) {
            throw new InvalidArgumentException('right padding must be >= 0');
        }
        if ($b < 0) {
            throw new InvalidArgumentException('bottom padding must be >= 0');
        }
        if ($l < 0) {
            throw new InvalidArgumentException('left padding must be >= 0');
        }
        if (($t === 0) && ($r === 0) && ($b === 0) && ($l === 0)) {
            throw new InvalidArgumentException('at least one padding must be > 0');
        }

        return $this->set(ProcessingOption::PADDING->value, $t, $r, $b, $l);
    }

    public function padding(): ?array
    {
        return $this->get(ProcessingOption::PADDING->value);
    }

    public function withTrim(int $threshold, string $color = '', bool ...$equalHorVer): self
    {
        $args = [];
        if (($color !== '') || (\count($equalHorVer) > 0)) {
            $args[] = $color;
        }
        if (\count($equalHorVer) > 0) {
            $args[] = (int)$equalHorVer[0];
        }
        if (\count($equalHorVer) > 1) {
            $args[] = (int)$equalHorVer[1];
        }

        return $this->set(ProcessingOption::TRIM->value, $threshold, ...$args);
    }

    /**
     * @see https://docs.imgproxy.net/generating_the_url_advanced?id=trim - Note #2
     * @return $this
     */
    public function withTrimTransparentBackground(int $threshold, bool $equalHor = false, bool $equalVer = false): self
    {
        return $this->withTrim($threshold, self::TRANSPARENT_BG, $equalHor, $equalVer);
    }

    public function trim(): ?array
    {
        return $this->get(ProcessingOption::TRIM->value);
    }

    public function withMaxBytes(int $bytes): self
    {
        if ($bytes <= 0) {
            throw new InvalidArgumentException('max_bytes must be greater than 0');
        }

        return $this->set(ProcessingOption::MAX_BYTES->value, $bytes);
    }

    public function maxBytes(): ?int
    {
        return $this->firstValue(ProcessingOption::MAX_BYTES->value, 'int');
    }

    public function withBackgroundRGB(int $r, int $g, int $b): self
    {
        if ($r < 0) {
            throw new InvalidArgumentException('RGB color Red component must be >= 0');
        }
        if ($r > 255) {
            throw new InvalidArgumentException('RGB color Red component must be <= 255');
        }
        if ($g < 0) {
            throw new InvalidArgumentException('RGB color Green component must be >= 0');
        }
        if ($g > 255) {
            throw new InvalidArgumentException('RGB color Green component must be <= 255');
        }
        if ($b < 0) {
            throw new InvalidArgumentException('RGB color Blue component must be >= 0');
        }
        if ($b > 255) {
            throw new InvalidArgumentException('RGB color Blue component must be <= 255');
        }

        return $this->set(ProcessingOption::BACKGROUND->value, $r, $g, $b);
    }

    public function withBackgroundHex(string $hexColor): self
    {
        if (\strlen($hexColor) !== 6) {
            throw new InvalidArgumentException('HEX color must be a string of 6 chars');
        }

        return $this->set(ProcessingOption::BACKGROUND->value, $hexColor);
    }

    public function background(): ?array
    {
        return $this->get(ProcessingOption::BACKGROUND->value);
    }

    public function withBackgroundAlpha(float $alpha): self
    {
        if (($alpha < 0) || ($alpha > 1)) {
            throw new InvalidArgumentException('background_alpha must be between 0 and 1');
        }

        return $this->set(ProcessingOption::BACKGROUND_ALPHA->value, $alpha);
    }

    public function backgroundAlpha(): ?float
    {
        return $this->firstValue(ProcessingOption::BACKGROUND_ALPHA->value, 'float');
    }

    public function withBrightness(int $v): self
    {
        if (($v < -255) || ($v > 255)) {
            throw new InvalidArgumentException('brightness must be between -255 and 255');
        }

        return $this->set(ProcessingOption::BRIGHTNESS->value, $v);
    }

    public function brightness(): ?int
    {
        return $this->firstValue(ProcessingOption::BRIGHTNESS->value, 'int');
    }

    public function withContrast(float $v): self
    {
        if (($v < 0) || ($v > 1)) {
            throw new InvalidArgumentException('contrast must be between 0 and 1');
        }

        return $this->set(ProcessingOption::CONTRAST->value, $v);
    }

    public function contrast(): ?float
    {
        return $this->firstValue(ProcessingOption::CONTRAST->value, 'float');
    }

    public function withSaturation(float $v): self
    {
        if (($v < 0) || ($v > 1)) {
            throw new InvalidArgumentException('saturation must be between 0 and 1');
        }

        return $this->set(ProcessingOption::SATURATION->value, $v);
    }

    public function saturation(): ?float
    {
        return $this->firstValue(ProcessingOption::SATURATION->value, 'float');
    }

    public function withBlur(float $sigma): self
    {
        if ($sigma <= 0) {
            throw new InvalidArgumentException('sigma must be greater than 0');
        }

        return $this->set(ProcessingOption::BLUR->value, $sigma);
    }

    public function blur(): ?float
    {
        return $this->firstValue(ProcessingOption::BLUR->value, 'float');
    }

    public function withSharpen(float $sigma): self
    {
        if ($sigma <= 0) {
            throw new InvalidArgumentException('sigma must be greater than 0');
        }

        return $this->set(ProcessingOption::SHARPEN->value, $sigma);
    }

    public function sharpen(): ?float
    {
        return $this->firstValue(ProcessingOption::SHARPEN->value, 'float');
    }

    public function withPixelate(int $size): self
    {
        if ($size <= 0) {
            throw new InvalidArgumentException('size must be greater than 0');
        }

        return $this->set(ProcessingOption::PIXELATE->value, $size);
    }

    public function pixelate(): ?int
    {
        return $this->firstValue(ProcessingOption::PIXELATE->value, 'int');
    }

    public function watermarkConfig(): ?array
    {
        return $this->get(ProcessingOption::WATERMARK->value);
    }

    public function withWatermarkUrl(string $url): self
    {
        return $this->withWatermarkEncodedUrl(base64_encode($url));
    }

    public function withWatermarkEncodedUrl(string $encodedUrl): self
    {
        return $this->set(ProcessingOption::WATERMARK_URL->value, $encodedUrl);
    }

    public function watermarkUrl(): ?string
    {
        $encoded = $this->firstValue(ProcessingOption::WATERMARK_URL->value, 'string');

        return $encoded ? base64_decode($encoded, true) : null;
    }

    public function withSvgCssStyle(string $css): self
    {
        return $this->withSvgEncodedCssStyle(base64_encode($css));
    }

    public function withSvgEncodedCssStyle(string $encodedCss): self
    {
        return $this->set(ProcessingOption::STYLE->value, $encodedCss);
    }

    public function svgCssStyle(): ?string
    {
        $encoded = $this->firstValue(ProcessingOption::STYLE->value, 'string');

        return $encoded ? base64_decode($encoded, true) : null;
    }

    public function withJpegOptions(
        bool $progressive = false,
        bool $noSubsample = false,
        bool $trellisQuant = false,
        bool $overshootDeringing = false,
        bool $optimizeScans = false,
        int $quantTable = 0
    ): self {
        if (($quantTable < 0) || ($quantTable > 8)) {
            throw new InvalidArgumentException('JPEG_QUANT_TABLE must be int 0-8');
        }

        return $this->set(
            ProcessingOption::JPEG_OPTIONS->value,
            (int)$progressive,
            (int)$noSubsample,
            (int)$trellisQuant,
            (int)$overshootDeringing,
            (int)$optimizeScans,
            $quantTable
        );
    }

    public function jpegOptions(): ?array
    {
        return $this->get(ProcessingOption::JPEG_OPTIONS->value);
    }

    public function withPngOptions(
        bool $interlaced = false,
        bool $quantize = false,
        int $quantizationColors = 256
    ): self {
        if (($quantizationColors < 2) || ($quantizationColors > 256)) {
            throw new InvalidArgumentException('PNG_QUANTIZATION_COLORS must be int 2-256');
        }

        return $this->set(
            ProcessingOption::PNG_OPTIONS->value,
            (int)$interlaced,
            (int)$quantize,
            $quantizationColors
        );
    }

    public function pngOptions(): ?array
    {
        return $this->get(ProcessingOption::PNG_OPTIONS->value);
    }

    public function withGifOptions(
        bool $optimizeFrames = false,
        bool $optimizeTransparency = false
    ): self {
        return $this->set(
            ProcessingOption::GIF_OPTIONS->value,
            (int)$optimizeFrames,
            (int)$optimizeTransparency
        );
    }

    public function gifOptions(): ?array
    {
        return $this->get(ProcessingOption::GIF_OPTIONS->value);
    }

    public function withPage(int $n): self
    {
        if ($n <= 0) {
            throw new InvalidArgumentException('page must be >= 0');
        }

        return $this->set(ProcessingOption::PAGE->value, $n);
    }

    public function page(): ?int
    {
        return $this->firstValue(ProcessingOption::PAGE->value, 'int');
    }

    public function withVideoThumbnailSecond(int $n): self
    {
        if ($n <= 0) {
            throw new InvalidArgumentException('video thumbnail second must be >= 0');
        }

        return $this->set(ProcessingOption::VIDEO_THUMBNAIL_SECOND->value, $n);
    }

    public function videoThumbnailSecond(): ?int
    {
        return $this->firstValue(ProcessingOption::VIDEO_THUMBNAIL_SECOND->value, 'int');
    }

    public function withPresets(string $preset1, string ...$morePresets): self
    {
        return $this->set(ProcessingOption::PRESET->value, $preset1, ...$morePresets);
    }

    public function presets(): ?array
    {
        return $this->get(ProcessingOption::PRESET->value);
    }

    public function withCacheBuster(string $id): self
    {
        return $this->set(ProcessingOption::CACHEBUSTER->value, $id);
    }

    public function cacheBuster(): ?string
    {
        return $this->firstValue(ProcessingOption::CACHEBUSTER->value, 'string');
    }

    public function withStripMetadata(): self
    {
        return $this->set(ProcessingOption::STRIP_METADATA->value, 1);
    }

    public function mustStripMetadata(): bool
    {
        return filter_var($this->firstValue(ProcessingOption::STRIP_METADATA->value, 'bool'), FILTER_VALIDATE_BOOL);
    }

    public function withStripColorProfile(): self
    {
        return $this->set(ProcessingOption::STRIP_COLOR_PROFILE->value, 1);
    }

    public function mustStripColorProfile(): bool
    {
        return filter_var(
            $this->firstValue(ProcessingOption::STRIP_COLOR_PROFILE->value, 'bool'),
            FILTER_VALIDATE_BOOL
        );
    }

    public function withAutoRotate(): self
    {
        return $this->set(ProcessingOption::AUTO_ROTATE->value, 1);
    }

    public function mustAutoRotate(): bool
    {
        return filter_var($this->firstValue(ProcessingOption::AUTO_ROTATE->value, 'bool'), FILTER_VALIDATE_BOOL);
    }

    public function withFilename(string $filename): self
    {
        return $this->set(ProcessingOption::FILENAME->value, $filename);
    }

    public function filename(): string
    {
        return $this->firstValue(ProcessingOption::FILENAME->value, 'string');
    }

    public function withFormat(string $format): self
    {
        return $this->set(ProcessingOption::FORMAT->value, $format);
    }

    public function format(): string
    {
        return $this->firstValue(ProcessingOption::FORMAT->value, 'string');
    }

    public function withQuality(int $quality): self
    {
        if ($quality < 0 || $quality > 100) {
            throw new InvalidArgumentException('quality must be >= 0 and <= 100');
        }

        return $this->set(ProcessingOption::QUALITY->value, $quality);
    }

    public function quality(): ?int
    {
        return $this->firstValue(ProcessingOption::QUALITY->value, 'int');
    }

    protected function firstValue(string $name, string $type)
    {
        $o = $this->get($name);
        if ((null === $o) || (\count($o) === 0)) {
            return null;
        }

        $val = $o[0];
        settype($val, $type);

        return $val;
    }

    private function set(string $name, ...$args): self
    {
        $this->options[$name] = $args;

        return $this;
    }

    private function get(string $name): ?array
    {
        return $this->options[$name] ?? null;
    }

    private function validateFocusPointGravity(float $x, float $y): void
    {
        if (($x < 0) || ($x > 1)) {
            throw new InvalidArgumentException('focus point gravity expects X in range 0-1');
        }
        if (($y < 0) || ($y > 1)) {
            throw new InvalidArgumentException('focus point gravity expects Y in range 0-1');
        }
    }

    private function gravityOptions(string $type = null, array $defaults, $x = null, $y = null): array
    {
        switch ($type) {
            case null:
                return $defaults;
            case Gravity::Smart->value:
                return [$type];
            case Gravity::North->value:
            case Gravity::South->value:
            case Gravity::East->value:
            case Gravity::West->value:
            case Gravity::NorthEast->value:
            case Gravity::NorthWest->value:
            case Gravity::SouthEast->value:
            case Gravity::SouthWest->value:
            case Gravity::Center->value:
                $x = (int)$x;
                $y = (int)$y;

                return [$type, $x, $y];
            case Gravity::FocusPoint->value:
                $x = (float)$x;
                $y = (float)$y;
                $this->validateFocusPointGravity($x, $y);

                return [$type, $x, $y];
            default:
                throw new InvalidArgumentException("unexpected gravity type {$type}");
        }
    }
}
