<?php

declare(strict_types=1);

namespace App\Setting\Domain\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class SettingYandexMetrika
{
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $number = null;

    public function __construct(
        int $number
    ) {}

    public function getNumber(): ?int
    {
        return $this->number;
    }
}
