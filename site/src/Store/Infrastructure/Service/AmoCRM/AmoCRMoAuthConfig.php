<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\AmoCRM;

use AmoCRM\OAuth\OAuthConfigInterface;
use App\Store\Domain\Entity\AmoCRM\AmoCRMoAccessToken;

readonly class AmoCRMoAuthConfig implements OAuthConfigInterface
{
    private AmoCRMoAccessToken $token;

    public function __construct(
        private AmoCRMoAccessTokenStorage $storage
    ) {
        $this->token = $this->storage->load();
    }

    public function getIntegrationId(): string
    {
        return $this->token->getIntegrationId();
    }

    public function getSecretKey(): string
    {
        return $this->token->getSecretKey();
    }

    public function getRedirectDomain(): string
    {
        return $this->token->getBaseDomain();
    }
}
