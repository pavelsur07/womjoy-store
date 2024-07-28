<?php

declare(strict_types=1);

namespace App\Banner\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\Table(name: '`banner_banners`')]
class Banner
{
    public const ACTIVE = 'active';
    public const INACTIVE = 'inactive';

    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 16)]
    private string $status;

    /** @var ArrayCollection<array-key, Item> */
    #[ORM\OneToMany(mappedBy: 'banner', targetEntity: Item::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $items;

    public function __construct(string $id, string $name)
    {
        Assert::uuid($id);
        $this->id = $id;
        $this->name = $name;
        $this->status = self::INACTIVE;
        $this->items = new ArrayCollection();
    }

    public function addItem(Item $item): void
    {
        $this->items->add($item);
    }

    public function activate(): void
    {
        $this->status = self::ACTIVE;
    }

    public function inactivate(): void
    {
        $this->status = self::INACTIVE;
    }

    public function isActive(): bool
    {
        return $this->status === self::ACTIVE;
    }

    public function isInactivate(): bool
    {
        return static::INACTIVE === $this->status;
    }

    public function getItems(): array
    {
        return $this->items->toArray();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getItem(string $item_id): ?Item
    {
        foreach ($this->items as $item) {
            if ($item->getId() === $item_id) {
                return $item;
            }
        }
        return null;
    }
}
