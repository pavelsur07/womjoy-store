<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product;

use App\Common\Infrastructure\Service\String\StringHelper;
use App\Common\Traits\GenerateMetadataTrait;
use App\Store\Domain\Entity\Attribute\Attribute;
use App\Store\Domain\Entity\Attribute\Variant as AttributeVariant;
use App\Store\Domain\Entity\Category\Category;
use App\Store\Domain\Entity\Product\ValueObject\ProductAggregateRating;
use App\Store\Domain\Entity\Product\ValueObject\ProductDimensions;
use App\Store\Domain\Entity\Product\ValueObject\ProductExport;
use App\Store\Domain\Entity\Product\ValueObject\ProductGarmentCare;
use App\Store\Domain\Entity\Product\ValueObject\ProductMarketplace;
use App\Store\Domain\Entity\Product\ValueObject\ProductPrice;
use App\Store\Domain\Entity\Product\ValueObject\ProductStatus;
use App\Store\Domain\Entity\Product\ValueObject\ProductYandexMarket;
use App\Store\Domain\Entity\SeoMetadata;
use App\Store\Domain\Exception\StoreCategoryException;
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
    use GenerateMetadataTrait;

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
    #[ORM\OrderBy(['sort' => 'ASC'])]
    private Collection $variants;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    private ?Category $mainCategory = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $slug = null;

    #[ORM\Embedded(class: SeoMetadata::class, columnPrefix: false)]
    private SeoMetadata $seoMetadata;

    #[ORM\Column(type: Types::STRING, length: 300, nullable: true)]
    private ?string $categoriesIds = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['default'=> '2023-06-03 06:16:11'])]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['default'=> '2023-06-03 06:16:11'])]
    private ?DateTimeImmutable $publishedAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['default'=> '2023-06-03 06:16:11'])]
    private DateTimeImmutable $updatedAt;

    #[ORM\Embedded(class: ProductAggregateRating::class, columnPrefix: false)]
    private ProductAggregateRating $rating;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $popularity = 0;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $weight = 0;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    private bool $isHasVariation = false;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    private bool $isPreSale = true;

    /** @var ArrayCollection<array-key, RelatedAssignment> */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: RelatedAssignment::class, cascade: ['all'], orphanRemoval: true)]
    private Collection $relatedAssignments;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Review::class, cascade: ['all'], orphanRemoval: true)]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    private Collection $reviews;

    /** @var ArrayCollection<array-key, AttributeAssignment> */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: AttributeAssignment::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $attributes;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: CategoryAssignment::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $categories;

    #[ORM\Embedded(class: ProductExport::class, columnPrefix: 'export_')]
    private ProductExport $export;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $deliveryNotes = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $sizeTable = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $measurementTable = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $modelParameters = null;

    #[ORM\Column(type: 'string', length: 600, nullable: true)]
    private ?string $fabrics = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $fabricComposition = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $goodsCare = null;

    /** @var ArrayCollection<array-key, Product> */
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'relatedColors')]
    #[ORM\JoinTable(name: 'store_product_related_colors')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'related_product_id', referencedColumnName: 'id')]
    private Collection $relatedColors;

    #[ORM\Embedded(class: ProductYandexMarket::class, columnPrefix: 'yandex_')]
    private ProductYandexMarket $yandexMarket;

    #[ORM\Embedded(class: ProductDimensions::class, columnPrefix: 'dimension_')]
    private ProductDimensions $dimensions;

    #[ORM\Embedded(class: ProductGarmentCare::class, columnPrefix: 'garment_care_')]
    private ProductGarmentCare $garmentCare;

    #[ORM\Column(type: 'text', length: 6000, nullable: true)]
    private ?string $searchData = null;

    #[ORM\Embedded(class: ProductMarketplace::class, columnPrefix: 'marketplace_')]
    private ProductMarketplace $marketplace;

    #[ORM\Column(type: 'text', length: 50, nullable: true)]
    private ?string $externalArticle = null;

    public function __construct(ProductPrice $price)
    {
        $this->price = $price;
        $this->status = new ProductStatus(ProductStatus::DRAFT);
        $this->images = new ArrayCollection();
        $this->variants = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
        $this->publishedAt = $this->createdAt;
        $this->rating = new ProductAggregateRating();
        $this->relatedAssignments = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->attributes = new ArrayCollection();
        $this->export = new ProductExport();
        $this->relatedColors = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function searchDataGenerate(): void
    {
        $this->searchData = $this->textConversion($this->getName()) . ', ' .
            $this->textConversion($this->getArticle()) . ', ' .
            $this->textConversion($this->getDescription()) . ', ' .
            $this->textConversion($this->getColorName());
    }

    public function getExternalArticle(): ?string
    {
        return $this->externalArticle;
    }

    public function changeExternalArticle(?string $externalArticle): void
    {
        if ($externalArticle !== null) {
            $this->externalArticle = mb_strtoupper($externalArticle);
        } else {
            $this->externalArticle = $externalArticle;
        }
    }

    public function getMarketplace(): ProductMarketplace
    {
        return $this->marketplace;
    }

    public function getSearchData(): ?string
    {
        return $this->searchData;
    }

    public function getGarmentCare(): ProductGarmentCare
    {
        return $this->garmentCare;
    }

    public function setGarmentCare(ProductGarmentCare $garmentCare): void
    {
        $this->garmentCare = $garmentCare;
    }

    public function getDimensions(): ProductDimensions
    {
        return $this->dimensions;
    }

    public function setDimensions(ProductDimensions $dimensions): void
    {
        $this->dimensions = $dimensions;
    }

    // Colors

    public function getRelatedColors(): Collection
    {
        return $this->relatedColors;
    }

    public function assignRelatedColors(self $product): void
    {
        if ($product->id === $this->getId()) {
            return;
        }

        foreach ($this->relatedColors as $relatedColor) {
            if ($relatedColor->getId() === $product->getId()) {
                return;
            }
        }

        $this->relatedColors->add($product);
    }

    public function revokeRelatedColors(self $product): void
    {
        foreach ($this->relatedColors as $color) {
            if ($color->getId() === $product->getId()) {
                $this->relatedColors->removeElement($color);
                return;
            }
        }
    }

    public function getColorName(): ?string
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getAttribute()->isColor() === true) {
                return $attribute->getVariant()->getName();
            }
        }

        return '';
    }

    public function getColor(): string
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getAttribute()->isColor() === true) {
                return $attribute->getVariant()->getColorValue();
            }
        }

        return '#C0C0C0';
    }

    // Attributes

    public function assignAttribute(
        Attribute $attribute,
        ?AttributeVariant $variant,
        ?string $customerValue = null
    ): void {
        $this->attributes->add(
            new AttributeAssignment(
                product: $this,
                attribute: $attribute,
                variant: $variant,
            )
        );

        /*if ($variant !== null) {
            if ($attribute->getId() !== $variant->getAttribute()->getId()) {
                throw new StoreProductException('Attribute not parent variant.');
            }

            foreach ($this->attributes as $item) {
                if ($item->getVariant()->getId() === $variant->getId()) {
                    return;
                }
            }

            $this->variants->add(
                new AttributeAssignment(
                    product: $this,
                    attribute: $attribute,
                    variant: $variant,
                    customerValue: null
                )
            );
        }*/
    }

    public function getFabrics(): ?string
    {
        return $this->fabrics;
    }

    public function setFabrics(?string $fabrics): void
    {
        $this->fabrics = $fabrics;
    }

    public function revokeAttribute(int $id): void {}

    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    // Model parameters & fabric composition & goods care

    public function getModelParameters(): ?string
    {
        return $this->modelParameters;
    }

    public function setModelParameters(?string $modelParameters): void
    {
        $this->modelParameters = $modelParameters;
    }

    public function getFabricComposition(): ?string
    {
        return $this->fabricComposition;
    }

    public function setFabricComposition(?string $fabricComposition): void
    {
        $this->fabricComposition = $fabricComposition;
    }

    public function getGoodsCare(): ?array
    {
        return $this->goodsCare;
    }

    public function setGoodsCare(?array $goodsCare): void
    {
        $this->goodsCare = $goodsCare;
    }

    // Notes

    public function getDeliveryNotes(): ?string
    {
        return $this->deliveryNotes;
    }

    public function changeDeliveryNotes(?string $deliveryNotes): void
    {
        $this->deliveryNotes = $deliveryNotes;
    }

    public function getSizeTable(): ?array
    {
        return $this->sizeTable;
    }

    public function changeSizeTable(?array $sizeTable): void
    {
        $this->sizeTable = $sizeTable;
    }

    public function getMeasurementTable(): ?array
    {
        return $this->measurementTable;
    }

    public function changeMeasurementTable(?array $measurementTable): void
    {
        $this->measurementTable = $measurementTable;
    }

    // Review

    public function addReview(int $vote, string $text, string $customerName): void
    {
        $this->reviews->add(
            new Review(
                product: $this,
                createdAt: new DateTimeImmutable(),
                vote: $vote,
                text: $text,
                customerName: $customerName,
            )
        );
    }

    public function removeReview(int $id): void
    {
        /** @var Review $review */
        foreach ($this->reviews as $review) {
            if ($review->getId() === $id) {
                $this->reviews->removeElement($review);
                return;
            }
        }

        throw new StoreProductException('Review not found.');
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    // Categories

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function assignCategory(Category $category): void
    {
        /** @var CategoryAssignment $item */
        foreach ($this->categories as $item) {
            if ($item->getCategory()->isEqualId($category->getId())) {
                return;
            }
        }
        $this->categories->add(new CategoryAssignment($this, $category));
    }

    public function revokeCategory(int $categoryId): void
    {
        /** @var CategoryAssignment $category */
        foreach ($this->categories as $category) {
            if ($category->getCategory()->isEqualId($categoryId)) {
                $this->categories->removeElement($category);
                return;
            }
        }

        throw new StoreCategoryException('Error not found category.');
    }

    // Export

    public function getExport(): ProductExport
    {
        return $this->export;
    }

    // Related
    public function assignRelatedProduct(self $product): void
    {
        $assignments = $this->relatedAssignments;
        foreach ($assignments as $assignment) {
            if ($assignment->isForProduct($product->getId())) {
                return;
            }
        }

        $this->relatedAssignments->add(
            new RelatedAssignment(
                product: $this,
                related: $product
            )
        );
    }

    public function revokeRelatedProduct(int $id): void
    {
        foreach ($this->relatedAssignments as $assignment) {
            if ($assignment->isForRelated($id)) {
                $this->relatedAssignments->removeElement($assignment);
                return;
            }
        }
        throw new StoreProductException('Assignment is not found.');
    }

    public function getRelatedAssignments(): Collection
    {
        return $this->relatedAssignments;
    }

    public function addVariant(string $value, ?string $barcode = null): void
    {
        $this->variants->add(new Variant(
            product: $this,
            article: $this->article . '-' . $value,
            value: $value,
            barcode: $barcode,
        ));
    }

    public function addVariantNewVersion(Variant $variant): void
    {
        $this->variants->add($variant);
    }

    /*    public function removeVariant(int $variantId): void
    {

    }*/

    public function getRating(): ProductAggregateRating
    {
        return $this->rating;
    }

    public function getMainCategory(): ?Category
    {
        return $this->mainCategory;
    }

    public function setMainCategory(?Category $mainCategory): void
    {
        $this->mainCategory = $mainCategory;
        $this->setCategoriesIds();
        // $this->regenerateSeoMetadataByTemplate();
    }

    public function regenerateSeoMetadataByTemplate(): void
    {
        if ($this->mainCategory === null) {
            throw new StoreProductException('Main category not setting.');
        }

        $metadata = new SeoMetadata();
        $metadata->setSeoTitle(
            $this->generateMetadata(
                $this->mainCategory->getTitleProductTemplate(),
                $this->getPlaceholders()
            )
        );
        $metadata->setSeoDescription(
            $this->generateMetadata(
                $this->mainCategory->getDescriptionProductTemplate(),
                $this->getPlaceholders()
            )
        );
        $metadata->setH1($this->getName());
        $this->setSeoMetadata($metadata);
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

    public function isPreSaleActive(): void
    {
        $this->isPreSale = true;
    }

    public function isPreSaleDisable(): void
    {
        $this->isPreSale = false;
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

    // Price
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

    public function setPopularity(int $popularity): void
    {
        $this->popularity = $popularity;
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
        $this->article = trim(mb_strtoupper($article));
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

    public function getPublishedAt(): ?DateTimeImmutable
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

    public function getAttributeValues(int $attributeId): array
    {
        $result = [];
        /** @var AttributeAssignment $attribute */
        foreach ($this->attributes as $attribute) {
            if ($attribute->getAttribute()->getId() === $attributeId) {
                $result[] = [
                    'value' => $attribute->getVariant()->getId(),
                    'label' => $attribute->getVariant()->getName(),
                ];
            }
        }

        return $result;
    }

    /**
     * @return array{name: string, article: int}
     */
    public function getPlaceholders(): array
    {
        return [
            'Name' => StringHelper::formatString($this->name),
            'name' => mb_strtolower($this->name),
            'article' => (string)$this->id,
            'listPrice' => (string)$this->getPrice()->getListPrice(),
            'price' => (string)$this->getPrice()->getPrice(),
        ];
    }

    public function getBrandName(): ?string
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getAttribute()->isBrand() === true) {
                return $attribute->getVariant()->getName();
            }
        }
        return null;
    }

    public function getPopularity(): int
    {
        return $this->popularity;
    }

    public function isHasVariation(): bool
    {
        return $this->isHasVariation;
    }

    public function isPreSale(): bool
    {
        return $this->isPreSale;
    }

    #[ORM\PreFlush]
    public function preFlush(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function clearAttributes(): void
    {
        $this->attributes->clear();
    }

    public function getYandexMarket(): ProductYandexMarket
    {
        return $this->yandexMarket;
    }

    public function getYandexMarketAttributes(): array
    {
        $result = [];

        /** @var AttributeAssignment $attribute */
        foreach ($this->attributes as $attribute) {
            $result[$attribute->getAttribute()->getId()] = [
                'name' => $attribute->getAttribute()->getName(),
                'value' => !empty($result[$attribute->getAttribute()->getId()]['value'])
                    ? $result[$attribute->getAttribute()->getId()]['value'] . ';' . $attribute->getVariant()->getName()
                    : $attribute->getVariant()->getName(),
            ];
        }
        return $result;
    }

    public function hasMoyskladIdInVariants(): bool
    {
        $callback = static fn (Variant $variant) => $variant->getMoyskladId();

        return $this->getVariants()->filter($callback)->count() === $this->getVariants()->count();
    }

    private function textConversion(null|int|string $text): string
    {
        if ($text === null) {
            return '';
        }

        return strtolower(strip_tags((string)$text));
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
