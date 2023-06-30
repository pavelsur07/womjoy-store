<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\Barcode;

use App\Matrix\Domain\Entity\Product\Variant;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`matrix_barcodes`')]
class Barcode
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\SequenceGenerator(sequenceName: 'matrix_barcodes_seq', allocationSize: 1, initialValue: 100000)]
    #[ORM\Column]
    private int $id;

    #[ORM\OneToOne(inversedBy: 'internalBarcode', targetEntity: Variant::class)]
    private Variant $variant;

    public function __construct(Variant $variant)
    {
        $this->variant = $variant;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getVariant(): Variant
    {
        return $this->variant;
    }

    public function getValue(): string
    {
        return '2000000' . $this->id;
    }
}
