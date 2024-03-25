<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Exception;

use Exception;

class ConflictException extends Exception
{
    public const HTTP_CODE = 409;

    public function __construct(?string $contents = null)
    {
        $message = '';
        $contents = @json_decode($contents) ?? null;

        if (isset($contents->reasonCode) && $contents->reasonCode) {
            $message = $contents->reasonCode;
        }

        parent::__construct($message, self::HTTP_CODE);
    }
}
