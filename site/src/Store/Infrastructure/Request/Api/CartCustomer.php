<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Request\Api;

final class CartCustomer
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $phone = null,
    ) {
    }
}
