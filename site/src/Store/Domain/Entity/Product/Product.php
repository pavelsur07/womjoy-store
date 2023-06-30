<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product;

use App\Store\Domain\Entity\Category\Category;
use App\Store\Domain\Entity\Product\ValueObject\ProductPrice;
use App\Store\Domain\Entity\Product\ValueObject\ProductStatus;
use App\Store\Domain\Entity\SeoMetadata;
use App\Store\Domain\Exception\StoreProductException;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Index(columns: ['popularity'], name: 'popularity_idx')]
#[ORM\Index(columns: ['published_at'], name: 'published_at_idx')]
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

    #[ORM\Embedded(class: SeoMetadata::class, columnPrefix: false)]
    private SeoMetadata $seoMetadata;

    #[ORM\Column(type: Types::STRING, length: 300, nullable: true)]
    private string|null $categoriesIds = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['default'=> '2023-06-03 06:16:11'])]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['default'=> '2023-06-03 06:16:11'])]
    private DateTimeImmutable|null $publishedAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['default'=> '2023-06-03 06:16:11'])]
    private DateTimeImmutable $updatedAt;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $popularity = 0;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $weight = 0;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    private bool $isHasVariation = false;

    public function __construct(ProductPrice $price)
    {
        $this->price = $price;
        $this->status = new ProductStatus(ProductStatus::DRAFT);
        $this->images = new ArrayCollection();
        $this->variants = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
        $this->publishedAt = $this->createdAt;
    }

    public function addVariant(string $value): void
    {
        $this->variants->add(new Variant(
            product: $this,
            article: $this->article . '-' . $value,
            value: $value
        ));
    }

    /*    public function removeVariant(int $variantId): void
        {

        }*/

    public function getMainCategory(): ?Category
    {
        return $this->mainCategory;
    }

    public function setMainCategory(?Category $mainCategory): void
    {
        $this->mainCategory = $mainCategory;
        $this->setCategoriesIds();
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

        /*if ($this->slug !== null) {
            throw new StoreProductException('Slug is not null.');
        }*/

        /*if ($slug === null) {
            $result = $this->mainCategory->getPrefixSlugProduct() . '-article-' . (int)$this->getId();
            $this->slug = mb_strtolower(trim($result));
            return;
        }*/

        $this->slug = $slug;
    }

    public function getCategoriesIds(): ?string
    {
        return $this->categoriesIds;
    }

    public function setCategoriesIds(): void
    {
        if ($this->mainCategory !== null) {
            $this->categoriesIds = $this->mainCategory->getIds();
        }
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

    /*public function addVariant(Variant $variant): void
    {
        if (!$this->variants->contains($variant)) {
            $this->variants->add($variant);
            $variant->setProduct($this);
        }

        return $this;
    }*/

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

        if ($this->status->isDraft()) {
            $this->publishedAt = new DateTimeImmutable();
        }

        $this->status = new ProductStatus(ProductStatus::ACTIVE);
    }

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function setArticle(?string $article): void
    {
        $this->article = $article;
    }

    public function getSeoMetadata(): SeoMetadata
    {
        return $this->seoMetadata;
    }

    public function setSeoMetadata(SeoMetadata $seoMetadata): void
    {
        $this->seoMetadata = $seoMetadata;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getPublishedAt(): DateTimeImmutable|null
    {
        return $this->publishedAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function isAvailable(): bool
    {
        return $this->status->isActive();
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @return array{name: string, article: integer}
     */
    public function getPlaceholders(): array
    {
        return [
            'name' => mb_strtolower($this->name),
            'article' => (string)$this->id,
        ];
    }

    #[ORM\PreFlush]
    public function preFlush(): void
    {
        $this->updatedAt = new DateTimeImmutable();
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
