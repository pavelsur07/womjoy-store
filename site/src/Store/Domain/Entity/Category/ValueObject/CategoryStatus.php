<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Category\ValueObject;

use App\Common\Domain\Entity\ValueObject\StringValueObject;
use App\Store\Domain\Exception\StoreCategoryException;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
class CategoryStatus extends StringValueObject
{
    public const DRAFT = 'draft';

    public const ACTIVE = 'active';

    public const DISABLE = 'disable';

    #[ORM\Column(type: Types::STRING, length: 20, options: ['default' => self::DRAFT])]
    protected $value;

    public function __construct(string $value)
    {
        Assert::oneOf($value, self::list());
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

    public function isDisable(): bool
    {
        return $this->value === self::DISABLE;
    }

    public function active(): void
    {
        if ($this->isActive()) {
            throw new StoreCategoryException('Already active category.');
        }

        $this->value = self::ACTIVE;
    }

    public function disable(): void
    {
        if ($this->isDisable()) {
            throw new StoreCategoryException('Already disabled category.');
        }

        if ($this->isDraft()) {
            throw new StoreCategoryException('Status not active.');
        }

        $this->value = self::DISABLE;
    }

    public static function list(): array
    {
        return [
            self::DRAFT,
            self::ACTIVE,
            self::DISABLE,
        ];
    }
}
