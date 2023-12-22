<?php

declare(strict_types=1);

namespace App\Setting\Domain\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class SettingUnisender
{
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $key = null;

    public function __construct(?string $key)
    {
        $this->key = $key;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }
}
