<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\JsonLd;

class JsonLdGenerator
{
    public static function generate(array $data): string
    {
        return '<script type="application/ld+json">' .
            json_encode(
                $data,
                JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE
            ) .
            '</script>';
    }
}
