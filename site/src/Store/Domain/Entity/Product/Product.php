<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product;

use App\Store\Domain\Entity\Category\Category;
use App\Store\Domain\Entity\Product\ValueObject\ProductPrice;
use App\Store\Domain\Entity\Product\ValueObject\ProductStatus;
use App\Store\Domain\Exception\StoreProductException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`store_products`')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 3000, nullable: true)]
    private ?string $description = null;

    #[ORM\Embedded(class: ProductPrice::class, columnPrefix: false)]
    private ProductPrice $price;

    #[ORM\Embedded(class: ProductStatus::class, columnPrefix: 'status_')]
    private ProductStatus $status;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $seoTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $seoDescription = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $article = null;

    /** @var ArrayCollection<array-key, Image> */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Image::class, cascade: ['all'], orphanRemoval: true)]
    #[ORM\OrderBy(['sort' => 'ASC'])]
    private Collection $images;

    /** @var ArrayCollection<array-key, Variant> */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Variant::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $variants;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    private Category|null $mainCategory = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private string|null $slug = null;

    public function __construct(ProductPrice $price)
    {
        $this->price = $price;
        $this->status = new ProductStatus(ProductStatus::DRAFT);
        $this->images = new ArrayCollection();
        $this->variants = new ArrayCollection();
    }

    public function getMainCategory(): ?Category
    {
        return $this->mainCategory;
    }

    public function setMainCategory(?Category $mainCategory): void
    {
        $this->mainCategory = $mainCategory;
    }

    public function imageUp(int $sortNumber): void
    {
        if ($sortNumber === 0) {
            throw new StoreProductException('Error sort number 0.');
        }

        $up = $sortNumber;
        $down = $sortNumber-1;

        $imageUp = $this->findImageBySortNumber($up);
        $imageDown = $this->findImageBySortNumber($down);

        $imageUp->setSort($up-1);
        $imageDown->setSort($down+1);
    }

    public function imageDown(int $sortNumber): void
    {
        $count = $this->images->count()-1;
        if ($sortNumber === $count) {
            throw new StoreProductException('Error sort number max.' . $count);
        }

        $down = $sortNumber;
        $up = $sortNumber+1;

        $imageDown = $this->findImageBySortNumber($down);
        $imageUp = $this->findImageBySortNumber($up);

        $imageDown->setSort($down+1);
        $imageUp->setSort($up-1);
    }

    public function findImageBySortNumber(int $sortNumber): Image
    {
        foreach ($this->images as $image) {
            if ($image->getSort() === $sortNumber) {
                return $image;
            }
        }

        throw new StoreProductException('Image not found this sort number ' . $sortNumber);
    }

    public function addImage(string $path, string $fileName, int $size): void
    {
        $count = \count($this->images);
        $this->images->add(
            new Image(
                product: $this,
                path: $path,
                name: $fileName,
                size: $size,
                sort: $count,
            )
        );
    }

    public function removeImage(int $imageId): void
    {
        $this->images->first();

        foreach ($this->images as $image) {
            if ($image->isEqualToId($imageId)) {
                $this->images->removeElement($image);
                $this->sortable();
                return;
            }
        }

        throw new StoreProductException('Image not found.');
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        if ($this->mainCategory === null) {
            throw new StoreProductException('Main category not set.');
        }
        /*
        if ($this->slug !== null) {
            throw new StoreProductException('Slug is not null.');
        }
        */

        $this->slug = mb_strtolower(trim($slug));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ProductPrice
    {
        return $this->price;
    }

    public function getStatus(): ProductStatus
    {
        return $this->status;
    }

    public function getSeoTitle(): ?string
    {
        return $this->seoTitle;
    }

    public function setSeoTitle(?string $seoTitle): self
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    public function getSeoDescription(): ?string
    {
        return $this->seoDescription;
    }

    public function setSeoDescription(?string $seoDescription): self
    {
        $this->seoDescription = $seoDescription;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function getImage(int $imageId): Image
    {
        foreach ($this->images as $image) {
            if ($image->isEqualToId($imageId)) {
                return $image;
            }
        }

        throw new StoreProductException('Image not found');
    }

    /**
     * @return Collection<int, Variant>
     */
    public function getVariants(): Collection
    {
        return $this->variants;
    }

    public function addVariant(Variant $variant): self
    {
        if (!$this->variants->contains($variant)) {
            $this->variants->add($variant);
            $variant->setProduct($this);
        }

        return $this;
    }

    public function removeVariant(Variant $variant): self
    {
        if ($this->variants->removeElement($variant)) {
            if ($variant->getProduct() === $this) {
                $variant->setProduct(null);
            }
        }

        return $this;
    }

    public function active(): void
    {
        if ($this->mainCategory === null) {
            throw new StoreProductException('Main category not set.');
        }
        $this->status = new ProductStatus(ProductStatus::ACTIVE);
    }

    private function sortable(): void
    {
        $this->images->first();
        $number = 0;
        foreach ($this->images as $image) {
            $image->setSort($number);
            ++$number;
        }
    }
}
