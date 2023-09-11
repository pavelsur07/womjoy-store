<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\String;

class StringHelper
{
    public static function formatString($inputString): string
    {
        $words = explode(' ', strtolower(trim($inputString)));
        $formattedWords = [];

        foreach ($words as $index => $word) {
            if ($index === 0) {
                $formattedWords[] = ucfirst($word);
            } else {
                $formattedWords[] = $word;
            }
        }

        return implode(' ', $formattedWords);
    }
}
