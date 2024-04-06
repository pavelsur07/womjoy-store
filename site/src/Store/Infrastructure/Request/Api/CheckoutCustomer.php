<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Request\Api;

use Symfony\Component\Validator\Constraints as Assert;

class CheckoutCustomer
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
        #[Assert\NotBlank]
        public string $phone,
        #[Assert\NotBlank]
        public string $email,
        public ?string $comment = null,

//        #[Assert\NotBlank]
//        public ?string $lastName = null,
    ) {}
}
