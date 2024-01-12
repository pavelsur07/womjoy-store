<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Order\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class ClientId
{
    #[ORM\Column(type: 'string', nullable: true)]
    private null|string $yandex = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private null|string $google = null;

    public function __construct(?string $yandex, ?string $google)
    {
        $this->yandex = $yandex;
        $this->google = $google;
    }

    public function getYandex(): ?string
    {
        return $this->yandex;
    }

    public function getGoogle(): ?string
    {
        return $this->google;
    }
}
