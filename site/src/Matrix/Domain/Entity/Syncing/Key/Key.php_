<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\Syncing\Key;

use App\Matrix\Domain\Entity\Seller\Seller;
use App\Matrix\Domain\Entity\Syncing\Key\ValueObject\WildberriesKey;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`matrix_syncing_keys`')]
class Key
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\ManyToOne(targetEntity: Seller::class)]
    private ?Seller $seller;

    #[ORM\Embedded(class: WildberriesKey::class, columnPrefix: 'wb_')]
    private WildberriesKey $wildberriesKey;

    public function __construct(string $name, Seller $seller, WildberriesKey $wildberriesKey)
    {
        $this->seller = $seller;
        $this->name = $name;
        $this->wildberriesKey = $wildberriesKey;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWildberriesKey(): WildberriesKey
    {
        return $this->wildberriesKey;
    }

    public function getSeller(): ?Seller
    {
        return $this->seller;
    }
}
