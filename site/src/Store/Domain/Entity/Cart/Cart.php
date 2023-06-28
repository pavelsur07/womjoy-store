<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Cart;

use App\Store\Domain\Entity\Product\Variant;
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

    public function __construct(DateTimeImmutable $createdAt)
    {
        $this->createdAt = $createdAt;
        $this->updatedAt = $createdAt;

        $this->items = new ArrayCollection();
    }

    public function add(Variant $variant, int $quantity): void
    {
    }

    public function remove(int $variantId): void
    {
    }

    public function setQuantity(int $variantId, int $quantity): void
    {
    }

    public function getWeight(): int
    {
        return 0;
    }

    public function getAmount(): int
    {
        return \count($this->items);
    }

    public function getCost(): void
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setCustomerId(int $customerId): void
    {
    }

    public function getCustomerId(): int
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
