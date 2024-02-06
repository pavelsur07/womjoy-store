<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Moysklad;

readonly class MoyskladStore
{
    public function __construct(
        private string $store
    ) {}

    public function get(): string
    {
        return $this->store;
    }
}
