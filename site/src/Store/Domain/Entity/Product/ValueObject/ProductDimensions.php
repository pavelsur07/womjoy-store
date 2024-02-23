<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class ProductDimensions
{
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $length = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $width = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $height = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?float $weight = null;

    public function __construct(?int $length = null, ?int $width = null, ?int $height = null, ?float $weight = null)
    {
        $this->length = $length;
        $this->width = $width;
        $this->height = $height;
        $this->weight = $weight;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }
}
