<?php

declare(strict_types=1);

namespace App\Setting\Domain\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class SettingYandexPay
{
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $apiKey = null;

    public function __construct(?string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }
}
