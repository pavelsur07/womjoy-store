<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Attribute;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`store_attribute_variants`')]
class Variant
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Attribute::class, inversedBy: 'variants')]
    private Attribute $attribute;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    public function __construct(Attribute $attribute, string $name)
    {
        $this->attribute = $attribute;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAttribute(): Attribute
    {
        return $this->attribute;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
