<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`store_product_related_assignments`')]
class RelatedAssignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'relatedAssignments')]
    private Product $product;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    private Product $related;

    public function __construct(Product $product, Product $related)
    {
        $this->product = $product;
        $this->related = $related;
    }

    public function isForProduct(int $id): bool
    {
        return $this->product->getId() === $id;
    }

    public function isForRelated(int $id): bool
    {
        return $this->related->getId() === $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getRelated(): Product
    {
        return $this->related;
    }
}
