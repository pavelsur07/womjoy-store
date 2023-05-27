<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Helper;

class ExternalService
{
    public const WB = 'wb';
    public const OZON = 'ozon';

    public const SBER_MARKET = 'sber_market';

    public static function list(): array
    {
        return [
            self::WB,
            self::OZON,
            self::SBER_MARKET,
        ];
    }
}
