<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class ProductMarketplace
{
    #[ORM\Column(type: 'string', length: 300, nullable: true, options: ['default' => null])]
    private ?string $wb = null;

    #[ORM\Column(type: 'string', length: 300, nullable: true, options: ['default' => null])]
    private ?string $ozon = null;

    #[ORM\Column(type: 'string', length: 500, nullable: true, options: ['default' => null])]
    private ?string $yandex = null;

    public function getWb(): ?string
    {
        return $this->wb;
    }

    public function setWb(?string $wb): void
    {
        $this->wb = $wb;
    }

    public function getOzon(): ?string
    {
        return $this->ozon;
    }

    public function setOzon(?string $ozon): void
    {
        $this->ozon = $ozon;
    }

    public function getYandex(): ?string
    {
        return $this->yandex;
    }

    public function setYandex(?string $yandex): void
    {
        $this->yandex = $yandex;
    }
}
