<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Placeholder;

final class PlaceholderService
{
    public static function replacePlaceholders(?string $string, array $data): string
    {
        if ($string === null) {
            $string = '';
        }
        foreach ($data as $key => $value) {
            $string = str_replace('[' . $key . ']', $value, $string);
        }
        return preg_replace('/\\[(.*?)\\]/', ' ', $string);
    }
}
