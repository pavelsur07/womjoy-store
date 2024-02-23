<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class ProductYandexMarket
{
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $model;

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): void
    {
        $this->model = $model;
    }
}
