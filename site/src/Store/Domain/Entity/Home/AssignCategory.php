<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Home;

use App\Store\Domain\Entity\Category\Category;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`store_home_page_assign_categories`')]
class AssignCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;
    #[ORM\ManyToOne(targetEntity: Home::class, inversedBy: 'categories')]
    private Home $home;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    private Category $category;

    public function __construct(Home $home, Category $category)
    {
        $this->home = $home;
        $this->category = $category;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getHome(): Home
    {
        return $this->home;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }
}
