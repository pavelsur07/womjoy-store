<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product;

use App\Store\Domain\Entity\Attribute\Attribute;
use App\Store\Domain\Entity\Attribute\Variant as AttributeVariant;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`store_product_attributes`')]
class AttributeAssignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'attributes')]
    private Product $product;

    #[ORM\ManyToOne(targetEntity: Attribute::class)]
    private Attribute $attribute;

    #[ORM\ManyToOne(targetEntity: AttributeVariant::class)]
    private ?AttributeVariant $variant = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $customerValue = null;

    public function __construct(Product $product, Attribute $attribute, ?AttributeVariant $variant, ?string $customerValue = null)
    {
        $this->product = $product;
        $this->attribute = $attribute;
        $this->variant = $variant;
        $this->customerValue = $customerValue;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getAttribute(): Attribute
    {
        return $this->attribute;
    }

    public function getVariant(): ?AttributeVariant
    {
        return $this->variant;
    }

    public function getCustomerValue(): ?string
    {
        return $this->customerValue;
    }
}
