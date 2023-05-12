<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\Product;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`matrix_product_images`')]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;
    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'images')]
    private Product $product;

    #[ORM\Column(type: Types::INTEGER)]
    private int $sort = 0;

    #[ORM\Column(type: Types::STRING)]
    private string $path;

    #[ORM\Column(type: Types::STRING)]
    private string $fileName;

    #[ORM\Column(type: Types::INTEGER)]
    private int $size;

    public function __construct(Product $product, int $sort, string $path, string $fileName, int $size)
    {
        $this->product = $product;
        $this->sort = $sort;
        $this->path = $path;
        $this->fileName = $fileName;
        $this->size = $size;
    }

    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    public function isEqualToId(int $imageId): bool
    {
        return $this->id === $imageId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
