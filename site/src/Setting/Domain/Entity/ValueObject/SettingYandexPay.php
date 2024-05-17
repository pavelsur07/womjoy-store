<?php

declare(strict_types=1);

namespace App\Setting\Domain\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class SettingYandexPay
{
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $apiKey = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $sandbox = true;


    public function __construct(?string $apiKey, ?bool $sandbox = true)
    {
        $this->apiKey = $apiKey;
        $this->sandbox = $sandbox;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function isSandbox(): ?bool
    {
        return $this->sandbox;
    }
}
