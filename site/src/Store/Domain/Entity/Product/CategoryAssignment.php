<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product;

use App\Store\Domain\Entity\Category\Category;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`store_product_categories`')]
class CategoryAssignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'variants')]
    private Product $product;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    private Category $category;

    public function __construct(Product $product, Category $category)
    {
        $this->product = $product;
        $this->category = $category;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }
}
