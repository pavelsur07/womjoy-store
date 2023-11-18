<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\AmoCRM;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiClientFactory;
use League\OAuth2\Client\Token\AccessToken;

readonly class AmoCRMClient
{
    public function __construct(
        private AmoCRMoAuthConfig $oAuthConfig,
        private AmoCRMoAuthService $oAuthService,
        private AmoCRMoAccessTokenStorage $storage,
    ) {}

    public function get(): AmoCRMApiClient
    {
        $token = $this->storage->load();

        $apiClientFactory = new AmoCRMApiClientFactory(
            oAuthConfig: $this->oAuthConfig,
            oAuthService: $this->oAuthService
        );

        $accessToken = new AccessToken(
            [
                'accessToken' => $token->getAccessToken(),
                'refreshToken' => $token->getRefreshToken(),
                'expires' => $token->getExpires(),
                'baseDomain' => $token->getBaseDomain(),
            ]
        );

        $apiClient = $apiClientFactory->make();
        $apiClient->setAccessToken($accessToken);

        return $apiClient;
    }
}
