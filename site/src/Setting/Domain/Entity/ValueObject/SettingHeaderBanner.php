<?php

declare(strict_types=1);

namespace App\Setting\Domain\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class SettingHeaderBanner
{
    private ?string $url = null;

    private ?string $promoText = null;

    public function __construct(?string $url, ?string $promoText)
    {
        $this->url = $url;
        $this->promoText = $promoText;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getPromoText(): ?string
    {
        return $this->promoText;
    }
}
