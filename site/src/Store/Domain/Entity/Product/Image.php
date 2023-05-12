<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product;

use App\Store\Infrastructure\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ORM\Table(name: '`store_product_images`')]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private Product $product;

    #[ORM\Column(length: 255)]
    private string $path;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column]
    private int $sort = 0;

    #[ORM\Column]
    private ?int $size = null;

    public function __construct(Product $product, ?string $path, string $name, int $size, int $sort)
    {
        $this->product = $product;
        $this->path = $path;
        $this->name = $name;
        $this->sort = $sort;
        $this->size = $size;
    }

    public function isEqualToId(int $imageId): bool
    {
        return $this->id === $imageId;
    }

    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }
}
