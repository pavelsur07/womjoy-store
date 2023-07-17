<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Order;

use App\Store\Domain\Entity\Order\ValueObject\OrderItemPrice;
use App\Store\Domain\Entity\Order\ValueObject\ProductData;
use App\Store\Domain\Entity\Product\Variant;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`store_order_items`')]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;
    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id', nullable: false)]
    private Order $order;

    #[ORM\ManyToOne(targetEntity: Variant::class)]
    private Variant $productVariant;

    #[ORM\Embedded(class: ProductData::class, columnPrefix: 'product_')]
    private ProductData $productData;

    #[ORM\Embedded(class: OrderItemPrice::class, columnPrefix: 'price_')]
    private OrderItemPrice $price;

    #[ORM\Column]
    private int $quantity = 1;

    public function __construct(Order $order, Variant $productVariant, ProductData $productData, OrderItemPrice $price, int $quantity)
    {
        $this->order = $order;
        $this->productVariant = $productVariant;
        $this->productData = $productData;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getProductVariant(): Variant
    {
        return $this->productVariant;
    }

    public function getProductData(): ProductData
    {
        return $this->productData;
    }

    public function getPrice(): OrderItemPrice
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
