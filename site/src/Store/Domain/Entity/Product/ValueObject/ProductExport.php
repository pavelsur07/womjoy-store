<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class ProductExport
{
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $yandexMarket = false;
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $sberMarket = false;

    public function isYandexMarket(): bool
    {
        return $this->yandexMarket;
    }

    public function setYandexMarket(bool $yandexMarket): void
    {
        $this->yandexMarket = $yandexMarket;
    }

    public function isSberMarket(): bool
    {
        return $this->sberMarket;
    }

    public function setSberMarket(bool $sberMarket): void
    {
        $this->sberMarket = $sberMarket;
    }
}
