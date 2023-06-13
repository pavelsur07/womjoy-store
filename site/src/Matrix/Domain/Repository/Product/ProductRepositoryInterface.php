<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Repository\Product;

use App\Matrix\Domain\Entity\Product\Product;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ProductRepositoryInterface
{
    public function get(int $id): Product;

    public function getAllIterable(): iterable;

    public function index(int $page, int $size, ProductFilterInterface $filter): PaginationInterface;

    public function list(): array;

    public function save(Product $product): void;

    public function remove(Product $product): void;

    public function findByArticle(string $article): ?Product;
}
