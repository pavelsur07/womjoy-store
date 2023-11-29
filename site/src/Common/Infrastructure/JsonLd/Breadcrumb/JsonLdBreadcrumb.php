<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\JsonLd\Breadcrumb;

class JsonLdBreadcrumb
{
    public static function generate(array $categories, string $baseUrl): array
    {
        $position = 1;
        $result = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position'=> $position,
                    'name'=> 'Главная',
                    'item'=> $baseUrl,
                ],
            ],
        ];

        foreach ($categories as $item) {
            $result['itemListElement'][] = [
                '@type' => 'ListItem',
                'position'=> ++$position,
                'name'=> $item['name'],
                'item'=> $baseUrl . $item['href'],
            ];
        }

        return $result;
    }
}
