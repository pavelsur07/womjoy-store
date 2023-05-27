<?php

declare(strict_types=1);

namespace App\Guarantee\Domain\Entity\ValueObject;

use App\Common\Domain\Entity\ValueObject\StringValueObject;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
class GuaranteeStatus extends StringValueObject
{
    public const NEW = 'new';
    public const CONFIRMED = 'confirmed';
    public const DELIVERED ='delivered';
    public const REJECT = 'reject';
    public const EXECUTED = 'executed';

    #[ORM\Column(type: Types::STRING, length: 20, options: ['default' => self::NEW])]
    protected $value;

    public function __construct(string $value)
    {
        Assert::oneOf($value, self::list());
        parent::__construct($value);
    }

    public static function list(): array
    {
        return [
            self::NEW,
            self::CONFIRMED,
            self::DELIVERED,
            self::REJECT,
            self::EXECUTED,
        ];
    }
}
