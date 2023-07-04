<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\JsonLd;

class JsonLdCompany
{
    public static function get(
        string $name = '',
        string $url = '',
        string $logo = '',
        string $postalCode = '',
        string $addressCountry = '',
        string $addressLocality = '',
        string $streetAddress = '',
        string $telephone = '',
        string $email = '',
    ): array {
        return [
            '@context' => 'http://schema.org',
            '@type' => 'Organization',
            'name' => $name,
            'url' => $url,
            'logo' => $logo,
            'address' => [
                '@type' => 'PostalAddress',
                'postalCode' => $postalCode,
                'addressCountry' => $addressCountry,
                'addressLocality' => $addressLocality,
                'streetAddress' => $streetAddress,
            ],
            'telephone' => $telephone,
            'email' => $email,
            /*'sameAs' =>
                [
                    'http://www.facebook.com/your-profile',
                    'http://www.twitter.com/yourProfile',
                    'http://plus.google.com/your_profile'
                ],
            */
        ];
    }
}
