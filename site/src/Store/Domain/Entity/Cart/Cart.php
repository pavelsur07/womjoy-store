<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Cart;

use App\Store\Domain\Entity\Product\Variant;
use App\Store\Domain\Exception\StoreCartException;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`store_carts`')]
class Cart
{
    public const STATUS_CART = 'new';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private int|null $customerId = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $updatedAt;

    /** @var ArrayCollection<array-key, CartItem> */
    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartItem::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $items;

    public function __construct(DateTimeImmutable $createdAt, int|null $customerId = null)
    {
        $this->createdAt = $createdAt;
        $this->updatedAt = $createdAt;
        $this->customerId = $customerId;
        $this->items = new ArrayCollection();
    }

    public function add(Variant $variant, int $quantity): void
    {
        foreach ($this->items as $item) {
            if ($item->getVariant()->getId() === $variant->getId()) {
                $item->plus($quantity);
                return;
            }
        }
        $this->items->add(new CartItem(cart: $this, variant: $variant, quantity: $quantity));
    }

    public function remove(int $variantId): void
    {
        foreach ($this->items as $item) {
            if ($item->getVariant()->getId() === $variantId) {
                $this->items->removeElement($item);
                return;
            }
        }
        throw new StoreCartException('Item Cart is not found.');
    }

    public function setQuantity(int $variantId, int $quantity): void
    {
        foreach ($this->items as $item) {
            if ($item->getVariant()->getId() === $variantId) {
                $item->changeQuantity($quantity);
                return;
            }
        }
        throw new StoreCartException('Item Cart is not found.');
    }

    public function getWeight(): int
    {
        return 0;
    }

    public function getAmount(): int
    {
        $result = 0;
        foreach ($this->items as $item) {
            $result += $item->getQuantity();
        }
        return $result;
    }

    public function getCost(): int
    {
        $result = 0;
        foreach ($this->items as $item) {
            $result += $item->getCost();
        }
        return $result;
    }

    public function getCostDiscount(): int
    {
        $result = 0;
        foreach ($this->items as $item) {
            $result += $item->getCostDiscount();
        }
        return $result;
    }

    public function getDiscount(): int
    {
        $result = 0;
        foreach ($this->items as $item) {
            $result += $item->getDiscount();
        }
        return $result;
    }

    public function getSubtotal(): int
    {
        $result = 0;
        foreach ($this->items as $item) {
            $result = $result + $item->getQuantity()*$item->getPrice();
        }
        return $result;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setCustomerId(int $customerId): void
    {
        $this->customerId = $customerId;
    }

    public function getCustomerId(): int|null
    {
        return $this->customerId;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /** @return ArrayCollection<CartItem> */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function clear(): void
    {
        foreach ($this->items as $item) {
            $this->items->removeElement($item);
        }
    }

    #[ORM\PreFlush]
    public function preFlush(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}
