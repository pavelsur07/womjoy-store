<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Cart;

use App\Store\Domain\Entity\Product\Variant;
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

    public function __construct(Cart $cart, Variant $variant)
    {
        $this->cart = $cart;
        $this->variant = $variant;
    }
}
