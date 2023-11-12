<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\AmoCRM;

use AmoCRM\OAuth\OAuthServiceInterface;
use App\Common\Infrastructure\Doctrine\Flusher;
use League\OAuth2\Client\Token\AccessTokenInterface;

readonly class AmoCRMoAuthService implements OAuthServiceInterface
{
    public function __construct(
        private AmoCRMoAccessTokenStorage $storage,
        private Flusher $flusher,
    ) {}

    public function saveOAuthToken(AccessTokenInterface $accessToken, string $baseDomain): void
    {
        $token = $this->storage->load();

        /*
        'accessToken' => $accessToken->getToken(),
        'refreshToken' => $accessToken->getRefreshToken(),
        'expires' => $accessToken->getExpires(),
        'baseDomain' => $baseDomain,
        */

        $token->setAccessToken($accessToken->getToken());
        $token->setRefreshToken($accessToken->getRefreshToken());
        $token->setExpires($accessToken->getExpires());
        $token->setBaseDomain($baseDomain);

        $this->flusher->flush();
    }
}
