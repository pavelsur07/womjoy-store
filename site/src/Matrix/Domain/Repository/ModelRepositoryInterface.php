<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Repository;

use App\Matrix\Domain\Entity\Model;

interface ModelRepositoryInterface
{
    public function get(int $id): Model;

    public function list(): array;

    public function save(Model $object, bool $flush = false): void;

    public function remove(Model $object, bool $flush = false): void;
}
