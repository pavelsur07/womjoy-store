<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Request\Api;

use Symfony\Component\Validator\Constraints as Assert;

readonly class MessageDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $topic,
        #[Assert\NotBlank]
        public string $name,
        #[Assert\Email]
        public string $email,
        #[Assert\NotBlank]
        public string $phone,
        #[Assert\NotBlank]
        public string $message,
    ) {}
}
