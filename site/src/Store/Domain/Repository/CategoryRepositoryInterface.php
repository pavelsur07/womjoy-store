<?php

declare(strict_types=1);

namespace App\Store\Domain\Repository;

use App\Store\Domain\Entity\Category\Category;

interface CategoryRepositoryInterface
{
    public function get(int $id): Category;

    public function list(): array;

    public function getCategoryTree(): array;

    public function getRootNodes(): array;

    public function save(Category $category): void;

    public function remove(Category $category): void;
}
