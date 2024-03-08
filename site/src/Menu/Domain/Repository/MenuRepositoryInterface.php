<?php

declare(strict_types=1);

namespace App\Menu\Domain\Repository;

use App\Menu\Domain\Entity\Menu;

interface MenuRepositoryInterface
{
    public function get(int $id): Menu;

    public function findById(int $id): ?Menu;

    public function list(): array;

    public function menuTree(Menu $menu): array;

    public function getRootAll(): array;

    public function save(Menu $object, bool $flush): void;

    public function remove(Menu $object, bool $flush): void;
}
