<?php

declare(strict_types=1);

namespace App\Guarantee\Application\Command\New;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class GuaranteeNewCommand
{
    public function __construct(
        #[Assert\Length(min: 6, max: 300)]
        #[Assert\NotBlank]
        private string $message,
        #[Assert\NotBlank]
        private string $service,
        #[Assert\NotBlank]
        #[Assert\Email]
        private string $email,
        #[Assert\NotBlank]
        private string $phone,
        #[Assert\IsTrue]
        private bool $isConfirmed,
        private bool|null $isSubscribe = null,
    ) {}

    public function isConfirmed(): bool
    {
        return $this->isConfirmed;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getService(): string
    {
        return $this->service;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getIsSubscribe(): ?bool
    {
        return $this->isSubscribe;
    }
}
