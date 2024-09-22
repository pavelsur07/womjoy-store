<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Exception;

use Exception;

/**
 * Неправильный запрос.
 */
class BadRequestException extends Exception
{
    public const HTTP_CODE = 400;

    public function __construct(?string $contents = null)
    {
        $message = '';
        $contents = @json_decode($contents) ?? null;

        if (isset($contents->reasonCode) && $contents->reasonCode) {
            $message = $contents->reasonCode;
        }

        if (isset($contents->details) && $contents->details) {
            $message = \sprintf('%s: %s', $message, json_encode($contents->details));
        }

        parent::__construct($message, self::HTTP_CODE);
    }
}
