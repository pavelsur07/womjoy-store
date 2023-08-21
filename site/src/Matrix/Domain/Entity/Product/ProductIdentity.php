<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\Product;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`matrix_product_identifiers`')]
class ProductIdentity
{
    public const ARTICLE = 'article';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'identifiers')]
    private Product $product;

    #[ORM\Column(type: 'string')]
    private string $value;

    #[ORM\Column(type: 'string')]
    private string $type;

    public function __construct(Product $product, string $value, string $type)
    {
        $this->product = $product;
        $this->value = $value;
        $this->type = $type;
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

    public function getType(): string
    {
        return $this->type;
    }
}
