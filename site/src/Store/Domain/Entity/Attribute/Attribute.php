<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Attribute;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`store_attributes`')]
class Attribute
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Column(type: Types::STRING)]
    private string $type;

    /** @var ArrayCollection<array-key, Variant> */
    #[ORM\OneToMany(mappedBy: 'attribute', targetEntity: Variant::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $variants;

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
        $this->variants = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getVariants(): Collection
    {
        return $this->variants;
    }
}
