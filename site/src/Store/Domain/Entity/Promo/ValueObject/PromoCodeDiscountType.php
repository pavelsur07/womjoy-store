<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Promo\ValueObject;

use App\Common\Domain\Entity\ValueObject\StringValueObject;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
class PromoCodeDiscountType extends StringValueObject
{
    public const SUM = 'sum';
    public const PERCENT = 'percent';

    #[ORM\Column(type: Types::STRING, length: 20)]
    protected $value;

    public function __construct(string $value = self::SUM)
    {
        Assert::oneOf($value, self::list());
        parent::__construct($value);
    }

    public static function list(): array
    {
        return [
            self::SUM,
            self::PERCENT,
        ];
    }
}
