<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Moysklad;

readonly class MoyskladCredentials
{
    public function __construct(
        private string $token
    ) {
    }

    public function get(): array
    {
        // ['login', 'password'] или ['token']
        return [$this->token];
    }
}
