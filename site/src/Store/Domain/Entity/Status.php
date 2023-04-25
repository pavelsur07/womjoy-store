<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity;

use Webmozart\Assert\Assert;

class Status
{
    public const DRAFT = 'draft';
    public const ACTIVE = 'active';
    public const HIDE = 'hide';
    public const ARCHIVE = 'archive';

    private string $value;

    public function __construct(string $value)
    {
        Assert::oneOf($value, self::list());
        $this->value = $value;
    }

    public function isDraft(): bool
    {
        return $this->value === self::DRAFT;
    }

    public function isActive(): bool
    {
        return $this->value === self::ACTIVE;
    }

    public function isHide(): bool
    {
        return $this->value === self::HIDE;
    }

    public function isArchive(): bool
    {
        return $this->value === self::ARCHIVE;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function list(): array
    {
        return [
            self::DRAFT,
            self::ACTIVE,
            self::HIDE,
            self::ARCHIVE,
        ];
    }
}
