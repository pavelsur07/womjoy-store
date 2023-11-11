<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\AmoCRM;

use AmoCRM\OAuth\OAuthConfigInterface;

readonly class AmoCRMoAuthConfig implements OAuthConfigInterface
{
    public function __construct(
        private AmoCRMoAccessTokenStorage $storage
    ) {}

    // Get clientId
    public function getIntegrationId(): string
    {
        $token = $this->storage->load();
        return $token->getClientId();
    }

    public function getSecretKey(): string
    {
        return '';
    }

    // Get RedirectDomain
    public function getRedirectDomain(): string
    {
        $token = $this->storage->load();
        return $token->getBaseDomain();
    }
}
