<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\AmoCRM;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiClientFactory;

readonly class AmoCRMClient
{
    public function __construct(
        private AmoCRMoAuthConfig $oAuthConfig,
        private AmoCRMoAuthService $oAuthService,
    ) {}

    public function get(): AmoCRMApiClient
    {
        $apiClientFactory = new AmoCRMApiClientFactory(
            oAuthConfig: $this->oAuthConfig,
            oAuthService: $this->oAuthService
        );

        return $apiClientFactory->make();
    }
}
