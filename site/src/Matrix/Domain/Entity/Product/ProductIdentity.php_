<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\Product;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`matrix_product_identity`')]
class ProductIdentity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'identity')]
    private Product $product;
    #[ORM\Column(type: 'string')]
    private string $value;

    public function __construct(Product $product, string $value)
    {
        $this->product = $product;
        $this->value = $value;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
