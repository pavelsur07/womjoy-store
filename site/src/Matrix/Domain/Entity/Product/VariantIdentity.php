<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\Product;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`matrix_product_variant_identifiers`')]
class VariantIdentity
{
    public const BARCODE = 'barcode';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Variant::class, inversedBy: 'identifiers')]
    private Variant $variant;
    #[ORM\Column(type: 'string')]
    private string $value;
    #[ORM\Column(type: 'string')]
    private string $type;

    public function __construct(Variant $variant, string $value, string $type)
    {
        $this->variant = $variant;
        $this->value = $value;
        $this->type = $type;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getVariant(): Variant
    {
        return $this->variant;
    }

    public function setVariant(Variant $variant): void
    {
        $this->variant = $variant;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
