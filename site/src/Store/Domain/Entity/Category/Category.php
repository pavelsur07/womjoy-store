<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Category;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
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
    private self|null $parent;

    /**
     * @var ArrayCollection<int, Category>
     */
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    #[ORM\OrderBy(['id' => 'ASC'])]
    private Collection $children;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private string|null $slug = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private string|null $prefixSlugProduct = null;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
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

    public function setPrefixSlugProduct(?string $prefixSlugProduct): void
    {
        $this->prefixSlugProduct = mb_strtolower(trim($prefixSlugProduct));
    }
}
