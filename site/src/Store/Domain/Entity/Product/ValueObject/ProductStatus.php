<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product\ValueObject;

use App\Common\Domain\Entity\ValueObject\StringValueObject;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
class ProductStatus extends StringValueObject
{
    public const DRAFT = 'draft';
    public const ACTIVE = 'active';
    public const HIDE = 'hide';
    public const ARCHIVE = 'archive';

    #[ORM\Column(type: Types::STRING, length: 20, options: ['default' => self::DRAFT])]
    protected $value;

    public function __construct(string $value)
    {
        Assert::oneOf($value, self::list());
        $this->value = $value;
        parent::__construct($value);
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

    public function hide(): void
    {
        $this->value = self::HIDE;
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
