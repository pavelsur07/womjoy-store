<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment;

use Voronkovich\SberbankAcquiring\Client as AcquiringClient;

class AlfaAcquiringClient extends AcquiringClient
{
    public const API_URI = 'https://pay.alfabank.ru';
    public const API_URI_TEST = 'https://alfa.rbsuat.com';

    public function __construct(
        readonly string $alfabankApi,
        readonly string $alfabankUsername,
        readonly string $alfabankPassword,
    ) {
        parent::__construct(
            ['apiUri' => $alfabankApi, 'userName' => $alfabankUsername, 'password' => $alfabankPassword]
        );
    }
}
