<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\AmoCRM;

use AmoCRM\Client\AmoCRMApiClientFactory;
use League\OAuth2\Client\Token\AccessToken;

class AmoCRMClient
{
    public function __construct(
        private AmoCRMoAuthConfig $oAuthConfig,
        private AmoCRMoAuthService $oAuthService,
    ) {}

    public function get(): void
    {
        $apiClientFactory = new AmoCRMApiClientFactory(
            oAuthConfig: $this->oAuthConfig,
            oAuthService: $this->oAuthService
        );
        $apiClient = $apiClientFactory->make();

        /*League\OAuth2\Client\Token\AccessToken
        $token = AccessToken::class;*/
    }
}
