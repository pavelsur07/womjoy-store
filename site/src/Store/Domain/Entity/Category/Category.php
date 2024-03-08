<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Category;

use App\Store\Domain\Entity\Attribute\Attribute;
use App\Store\Domain\Entity\Category\ValueObject\CategoryImage;
use App\Store\Domain\Entity\Category\ValueObject\CategoryMenu;
use App\Store\Domain\Entity\Category\ValueObject\CategoryStatus;
use App\Store\Domain\Entity\SeoMetadata;
use App\Store\Domain\Exception\StoreCategoryException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`store_categories`')]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?self $parent;

    /**
     * @var ArrayCollection<int, Category>
     */
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class, cascade: ['ALL'], orphanRemoval: true)]
    #[ORM\OrderBy(['id' => 'ASC'])]
    private Collection $children;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $prefixSlugProduct = null;

    #[ORM\Embedded(class: SeoMetadata::class, columnPrefix: false)]
    private SeoMetadata $seoMetadata;

    #[ORM\Column(type: Types::STRING, length: 300, nullable: true)]
    private ?string $ids = null;

    #[ORM\Embedded(class: CategoryImage::class, columnPrefix: 'image_')]
    private ?CategoryImage $image;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $titleProductTemplate = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $descriptionProductTemplate = null;

    #[ORM\Embedded(class: CategoryStatus::class, columnPrefix: 'status_')]
    private CategoryStatus $status;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: AttributeAssignment::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $attributes;

    #[ORM\Column(type: 'json', options: ['default'=>'{}'])]
    private array $filters = [];

    #[ORM\Embedded(class: CategoryMenu::class, columnPrefix: 'menu_')]
    private CategoryMenu $menu;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->image = new CategoryImage();
        $this->status = new CategoryStatus(CategoryStatus::DRAFT);
        $this->attributes = new ArrayCollection();
        $this->filters = [];
    }

    // Filter

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function updateFilters(array $filters): void
    {
        $this->filters = $filters;
    }

    // Attribute

    public function assignAttribute(Attribute $attribute): void
    {
        /** @var AttributeAssignment $item */
        foreach ($this->attributes as $item) {
            if ($item->getAttribute()->getId() === $attribute->getId()) {
                throw new StoreCategoryException('Already assign attribute.');
            }
        }

        $this->attributes->add(new AttributeAssignment($this, $attribute));
    }

    public function revokeAttribute(int $id): void
    {
        /** @var AttributeAssignment $item */
        foreach ($this->attributes as $item) {
            if ($item->getAttribute()->getId() === $id) {
                $this->attributes->removeElement($item);
                return;
            }
        }

        throw new StoreCategoryException('Not found attribute.');
    }

    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    // Status

    public function active(): void
    {
        if ($this->slug === null) {
            throw new StoreCategoryException('Slug is not setting.');
        }

        $this->status->active();
    }

    public function disable(): void
    {
        $this->status->disable();
    }

    public function addSubCategory(string $name): void
    {
        $newChild = new self();
        $newChild->setName($name);
        $newChild->setParent($this);
        $this->children->add($newChild);
        $this->image = new CategoryImage();
    }

    public function isRoot(): bool
    {
        return $this->parent === null;
    }

    public function removeSubCategory(int $id): void
    {
        foreach ($this->children as $child) {
            if ($id === $child->getId()) {
                $this->children->removeElement($child);
                return;
            }
        }

        throw new StoreCategoryException('Sub category not found');
    }

    public function generateIds(): void
    {
        if ($this->parent === null) {
            $this->ids = (string)$this->id;
        } else {
            $this->ids = $this->parent->ids . '/' . (string)$this->id;
        }

        if (\count($this->getChildren()) > 0) {
            foreach ($this->children as $child) {
                $child->generateIds();
            }
        }
    }

    public function getStatus(): CategoryStatus
    {
        return $this->status;
    }

    public function getIds(): ?string
    {
        return $this->ids;
    }

    public function getSeoMetadata(): SeoMetadata
    {
        return $this->seoMetadata;
    }

    public function setSeoMetadata(SeoMetadata $seoMetadata): void
    {
        $this->seoMetadata = $seoMetadata;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isEqualId(int $id): bool
    {
        return $this->id === $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): void
    {
        $this->parent = $parent;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function setChildren(Collection $children): void
    {
        $this->children = $children;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = mb_strtolower(trim($slug));
    }

    public function getPrefixSlugProduct(): ?string
    {
        return $this->prefixSlugProduct;
    }

    public function getImage(): ?CategoryImage
    {
        return $this->image;
    }

    public function setImage(CategoryImage $image): void
    {
        $this->image = $image;
    }

    public function setPrefixSlugProduct(?string $prefixSlugProduct): void
    {
        if ($prefixSlugProduct !== null) {
            $this->prefixSlugProduct = mb_strtolower(trim($prefixSlugProduct));
        }
    }

    public function getTitleProductTemplate(): ?string
    {
        return $this->titleProductTemplate;
    }

    public function setTitleProductTemplate(?string $titleProductTemplate): void
    {
        $this->titleProductTemplate = $titleProductTemplate;
    }

    public function getDescriptionProductTemplate(): ?string
    {
        return $this->descriptionProductTemplate;
    }

    public function setDescriptionProductTemplate(?string $descriptionProductTemplate): void
    {
        $this->descriptionProductTemplate = $descriptionProductTemplate;
    }

    public function getMenu(): CategoryMenu
    {
        return $this->menu;
    }

    #[ORM\PostLoad()]
    public function checkEmbeds(): void
    {
        if ($this->image->isEmpty()) {
            $this->image = null;
        }
    }
}
