<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Category\ValueObject;

use App\Common\Domain\Entity\ValueObject\StringValueObject;
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

    public static function list(): array
    {
        return [
            self::DRAFT,
            self::ACTIVE,
            self::DISABLE,
        ];
    }
}
