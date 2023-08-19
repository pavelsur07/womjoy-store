<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Category;

use App\Store\Domain\Entity\Attribute\Attribute;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`store_category_attributes`')]
class AttributeAssignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'attributes')]
    private Category $category;

    #[ORM\ManyToOne(targetEntity: Attribute::class)]
    private Attribute $attribute;

    public function __construct(Category $category, Attribute $attribute)
    {
        $this->category = $category;
        $this->attribute = $attribute;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getAttribute(): Attribute
    {
        return $this->attribute;
    }
}
