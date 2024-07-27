<?php

declare(strict_types=1);

namespace App\Common\Traits;

trait GenerateMetadataTrait
{
    public function generateMetadata(?string $string, array $data): string
    {
        if ($string === null) {
            $string = '';
        }
        foreach ($data as $key => $value) {
            $string = str_replace('[' . $key . ']', $value, $string);
        }
        return preg_replace('/\[(.*?)\]/', ' ', $string);
    }
}
