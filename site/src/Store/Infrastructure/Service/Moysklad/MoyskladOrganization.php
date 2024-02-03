<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Moysklad;

readonly class MoyskladOrganization
{
    public function __construct(
        private string $organization
    ) {
    }

    public function get(): string
    {
        return $this->organization;
    }
}
