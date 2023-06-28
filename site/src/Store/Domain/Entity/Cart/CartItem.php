<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Cart;

use App\Store\Domain\Entity\Product\Variant;
use App\Store\Domain\Exception\StoreCartException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`store_cart_items`')]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'items')]
    private Cart $cart;

    #[ORM\ManyToOne(targetEntity: Variant::class)]
    private Variant $variant;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $quantity = 1;

    public function __construct(Cart $cart, Variant $variant, int $quantity = 1)
    {
        if (!$variant->canBeCheckout($quantity)) {
            throw new StoreCartException('Quantity is too big.');
        }

        $this->cart = $cart;
        $this->variant = $variant;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function getVariant(): Variant
    {
        return $this->variant;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getCost(): int
    {
        return $this->getPrice() * $this->quantity;
    }

    public function getPrice(): int
    {
        return $this->variant->getProduct()->getPrice()->getListPrice();
    }

    public function getWeight(): int
    {
        return $this->variant->getProduct()->getWeight() * $this->quantity;
    }
}
