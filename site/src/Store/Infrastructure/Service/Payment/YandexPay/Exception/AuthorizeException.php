<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Exception;

use Exception;

/**
 * Ошибка авторизации. Не установлен заголовок.
 */
class AuthorizeException extends Exception
{
    public const HTTP_CODE = 401;

    public function __construct(?string $contents = null)
    {
        $message = '';
        $contents = @json_decode($contents) ?? null;

        if (isset($contents->reason) && $contents->reason) {
            $message = $contents->reason;
        }

        parent::__construct($message, self::HTTP_CODE);
    }
}
