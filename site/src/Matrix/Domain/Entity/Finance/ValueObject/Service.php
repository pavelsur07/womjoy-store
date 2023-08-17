<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\Finance\ValueObject;

use App\Common\Domain\Entity\ValueObject\StringValueObject;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
class Service extends StringValueObject
{
    public const WB = 'wb';
    public const OZON = 'ozon';

    public const YANDEX_M = 'ym';

    public const IM = 'im';

    public const SBER_M = 'sbm';

    #[ORM\Column(type: 'string', length: 16)]
    protected $value;

    public function __construct($value)
    {
        Assert::oneOf($value, self::list());
        $this->value = $value;
        parent::__construct($value);
    }

    public static function list(): array
    {
        return [
            self::IM,
            self::OZON,
            self::WB,
            self::YANDEX_M,
        ];
    }
}
