<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Request\Api;

use Symfony\Component\Validator\Constraints as Assert;

class CheckoutDelivery
{
    public function __construct(
        #[Assert\NotBlank]
        public string $address,
        public ?float $price = 0,
    ) {
    }
}
