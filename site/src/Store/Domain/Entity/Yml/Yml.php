<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Yml;

use App\Store\Domain\Entity\Product\Product;
use App\Store\Domain\Exception\StoreException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`store_yml`')]
class Yml
{
    public const ACTIVE = 'active';
    public const DISABLE = 'disable';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;
    #[ORM\Column]
    private string $name;

    #[ORM\Column]
    private string $status;

    #[ORM\Column]
    private string $fileName;

    #[ORM\Column]
    private string $path;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isYandexMarketFid;

    /** @var ArrayCollection<array-key, Item> */
    #[OneToMany(mappedBy: 'yml', targetEntity: Item::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $items;

    public function __construct(string $name, string $fileName, string $path)
    {
        $this->name = $name;
        $this->status = self::DISABLE;
        $this->fileName = $fileName;
        $this->path = $path;
        $this->items = new ArrayCollection();
        $this->setIsYandexMarketFid(false);
    }

    public function isYandexMarketFid(): bool
    {
        return $this->isYandexMarketFid;
    }

    public function setIsYandexMarketFid(bool $isYandexMarketFid): void
    {
        $this->isYandexMarketFid = $isYandexMarketFid;
    }

    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    public function active(): void
    {
        $this->status = self::ACTIVE;
    }

    public function disable(): void
    {
        $this->status = self::DISABLE;
    }

    public function add(Product $product): void
    {
        foreach ($this->items as $item) {
            if ($item->getProduct()->getId() === $product->getId()) {
                return;
            }
        }

        $this->items->add(new Item($this, $product));
    }

    public function remove(int $productId): void
    {
        foreach ($this->items as $item) {
            if ($item->getProduct()->getId() === $productId) {
                $this->items->removeElement($item);
                return;
            }
        }

        throw new StoreException('Yml item product not found.');
    }

    public function getId(): int
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

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }
}
