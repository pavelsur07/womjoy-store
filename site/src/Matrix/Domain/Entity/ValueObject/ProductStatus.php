<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\ValueObject;

use App\Common\Domain\Entity\ValueObject\StringValueObject;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
class ProductStatus extends StringValueObject
{
    public const DRAFT = 'draft';
    public const DEVELOPMENT = 'development';
    public const READY_DEVELOPMENT = 'ready-development';
    public const WAIT_SALE = 'wait-sale';
    public const READY_SALE = 'ready-sale';
    public const SALE = 'sale';
    public const ARCHIVED = 'archived';

    #[ORM\Column(type: 'string', length: 30, options: ['default'=> self::DRAFT])]
    protected $value;

    public function __construct($value)
    {
        Assert::oneOf($value, self::list());
        $this->value = $value;
        parent::__construct($value);
    }

    public function development(): void
    {
        $this->value = self::DEVELOPMENT;
    }

    public function readyDevelopment(): void
    {
        $this->value = self::READY_DEVELOPMENT;
    }

    public function waitSale(): void
    {
        $this->value = self::WAIT_SALE;
    }

    public function readySale(): void
    {
        $this->value = self::READY_SALE;
    }

    public function sale(): void
    {
        $this->value = self::SALE;
    }

    public function archived(): void
    {
        $this->value = self::ARCHIVED;
    }

    public function setStatus(string $value): void
    {
        Assert::oneOf($value, self::list());
        $this->value = $value;
    }

    public static function list(): array
    {
        return [
            self::DRAFT,
            self::DEVELOPMENT,
            self::READY_DEVELOPMENT,
            self::WAIT_SALE,
            self::READY_SALE,
            self::SALE,
            self::ARCHIVED,
        ];
    }
}
