<?php

namespace App\Entity;

use App\Repository\ImageRepository;
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
    private ?Product $product = null;

    #[ORM\Column(length: 255)]
    private ?string $patch = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $sort = null;

    #[ORM\Column]
    private ?int $size = null;

    /**
     * @param Product|null $product
     * @param string|null  $patch
     * @param string|null  $name
     * @param int|null     $sort
     * @param int|null     $size
     */
    public function __construct(Product $product, ?string $patch, ?string $name, ?int $size,?int $sort)
    {
        $this->product = $product;
        $this->patch = $patch;
        $this->name = $name;
        $this->sort = $sort;
        $this->size = $size;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getPatch(): ?string
    {
        return $this->patch;
    }

    public function setPatch(string $patch): self
    {
        $this->patch = $patch;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSort(): ?int
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

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }
}
