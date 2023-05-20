<?php

declare(strict_types=1);

namespace App\Menu\Domain\Entity;

use App\Menu\Domain\Exception\MenuException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`menu_menus`')]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'string')]
    private string $href;

    #[ORM\Column(type: Types::INTEGER)]
    private int $sort = 0;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private self|null $parent;

    /**
     * @var ArrayCollection<int, Menu>
     */
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    #[ORM\OrderBy(['sort' => 'ASC'])]
    private Collection $children;

    public function __construct(string $name, string $href)
    {
        $this->name = $name;
        $this->href = $href;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setChildOf(self $parent): void
    {
        if ($parent === $this->parent) {
            return;
        }

        /** @psalm-var  Menu $current */
        $current = $parent;
        do {
            if ($current === $this) {
                throw new MenuException('Cyclomatic children menu.');
            }
        } while ($current && $current = $current->parent);

        $this->parent = $parent;
        $parent->children->add($this);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function setHref(string $href): void
    {
        $this->href = $href;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): void
    {
        $this->sort = $sort;
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
}
