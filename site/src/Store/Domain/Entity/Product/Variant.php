<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product;

use App\Store\Domain\Exception\StoreProductException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`store_product_variants`')]
class Variant
{
    public const SORT = [
        'XS'=> 0,
        'S' => 10,
        'M' => 20,
        'L' => 30,
        'S-M' => 40,
        'M-L' => 50,
    ];
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

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $barcode = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $quantity = 0;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $moyskladId = null;

    #[ORM\Column(type: 'string', options: ['default' => 0])]
    private int $sort = 0;

    public function __construct(Product $product, string $article, string $value, ?string $barcode = null)
    {
        $this->article = $article;
        $this->value = $value;
        $this->product = $product;
        if ($barcode !== null) {
            $this->barcode = trim($barcode);
        }
    }

    public function sortGenerate(): void
    {
        foreach (self::SORT as $key => $value) {
            if ($this->value === $key) {
                $this->sort = $value;
            }
        }
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(?string $barcode): void
    {
        if ($this->barcode !== null) {
            throw new StoreProductException('Barcode is not null.');
        }

        $this->barcode = $barcode;
    }

    public function changeValue(string $value): void
    {
        $this->value = $value;
    }

    public function changeQuantity(int $value): void
    {
        $this->quantity = $value;
    }

    public function changeBarcode(?string $barcode = null): void
    {
        if ($this->barcode !== null) {
            $this->barcode = trim($barcode);
        }
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

    /** @deprecated  */
    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function canBeAddToCart(): bool
    {
        if ($this->product->isPreSale()) {
            return true;
        }

        return 1 <= $this->quantity;
    }

    public function canBeCheckout($quantity): bool
    {
        if ($this->product->isPreSale()) {
            return true;
        }

        return $quantity <= $this->quantity;
    }

    public function checkout(int $quantity): void
    {
        if (!$this->product->isPreSale()) {
            if ($quantity > $this->quantity) {
                throw new StoreProductException('Only ' . $this->quantity . ' items are available.');
            }
            $this->setQuantity($this->quantity - $quantity);
        }
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getMoyskladId(): ?string
    {
        return $this->moyskladId;
    }

    public function setMoyskladId(?string $moyskladId): self
    {
        $this->moyskladId = $moyskladId;

        return $this;
    }

    private function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }
}
