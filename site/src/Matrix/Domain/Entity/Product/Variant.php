<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\Product;

use App\Matrix\Domain\Entity\ValueObject\VariantBarcode;
use App\Matrix\Domain\Entity\ValueObject\VariantValue;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`matrix_product_variants`')]
class Variant
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'variants')]
    private Product $product;

    #[ORM\Embedded(class: VariantBarcode::class, columnPrefix: 'barcode_')]
    private VariantBarcode $barcode;
    #[ORM\Embedded(class: VariantValue::class, columnPrefix: false)]
    private VariantValue $value;

    #[ORM\Column(type: Types::STRING)]
    private string $article;

    public function __construct(Product $product, VariantBarcode $barcode, VariantValue $value)
    {
        $this->product = $product;
        $this->barcode = $barcode;
        $this->value = $value;
        $this->article = $product->getArticle() . '-' . mb_strtoupper($value->value());
    }

    public function isEquivalentTo(self $variant): bool
    {
        return $this->id === $variant->id;
    }

    public function isEquivalentToBarcode(VariantBarcode $barcode): bool
    {
        return $this->getBarcode()->value() === $barcode->value();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getBarcode(): VariantBarcode
    {
        return $this->barcode;
    }

    public function getValue(): VariantValue
    {
        return $this->value;
    }

    public function getArticle(): string
    {
        return $this->article;
    }
}