<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product;

use App\Store\Domain\Exception\StoreProductException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`store_product_variants`')]
class Variant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $article = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'variants')]
    private ?Product $product = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $quantity = 0;

    public function __construct(Product $product, string $article, string $value)
    {
        $this->article = $article;
        $this->value = $value;
        $this->product = $product;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function setArticle(?string $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function canBeAddToCart(): bool
    {
        return 1 <= $this->quantity;
    }

    public function canBeCheckout($quantity): bool
    {
        return $quantity <= $this->quantity;
    }

    public function checkout(int $quantity): void
    {
        if ($quantity > $this->quantity) {
            throw new StoreProductException('Only ' . $this->quantity . ' items are available.');
        }
        $this->setQuantity($this->quantity - $quantity);
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    private function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }
}
