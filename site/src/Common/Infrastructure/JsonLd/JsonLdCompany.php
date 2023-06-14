<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\JsonLd;

class JsonLdCompany
{
    public static function get(): array
    {
        return [
            '@context' => 'http://schema.org',
            '@type' => 'Organization',
            'name' => 'WomJoy - женская одежда для спорта и отдыха',
            'url' => 'https://womjoy.ru',
            'logo' => 'https://womjoy.ru/img/logo.svg',
            'address' => [
                '@type' => 'PostalAddress',
                'postalCode' => '346780',
                'addressCountry' => 'Российская Федерация',
                'addressLocality' => 'г. Азов',
                'streetAddress' => 'ул. Ленина 53',
            ],
            'telephone' => '8(800)301-67-53',
            'email' => 'info@womjoy.ru',
            /*'sameAs' =>
                [
                    'http://www.facebook.com/your-profile',
                    'http://www.twitter.com/yourProfile',
                    'http://plus.google.com/your_profile'
                ],*/
        ];
    }
}
