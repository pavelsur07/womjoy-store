<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Yml;

use App\Store\Domain\Entity\Product\Product;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`store_yml_items`')]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Yml::class, inversedBy: 'items')]
    private Yml $yml;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    private Product $product;

    public function __construct(Yml $yml, Product $product)
    {
        $this->yml = $yml;
        $this->product = $product;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getYml(): Yml
    {
        return $this->yml;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }
}
