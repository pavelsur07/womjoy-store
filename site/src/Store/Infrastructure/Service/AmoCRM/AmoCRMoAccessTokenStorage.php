<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\AmoCRM;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\AmoCRM\AmoCRMoAccessToken;
use App\Store\Infrastructure\Repository\AmoCRMoAccessTokenRepository;

readonly class AmoCRMoAccessTokenStorage
{
    public function __construct(
        private AmoCRMoAccessTokenRepository $tokens,
        private Flusher $flusher,
    ) {}

    public function load(): AmoCRMoAccessToken
    {
        $token = $this->tokens->find();

        if ($token !== null) {
            return $token;
        }

        $newToken = new AmoCRMoAccessToken();
        $this->tokens->save($newToken, true);
        return $newToken;
    }
}
