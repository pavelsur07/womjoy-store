<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Repository;

use App\Matrix\Domain\Entity\Product\Product;

interface ProductRepositoryInterface
{
    public function get(int $id): Product;

    public function list(): array;

    public function save(Product $product): void;

    public function remove(Product $product): void;

    public function findByArticle(string $article): ?Product;
}
