<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Repository;

use App\Matrix\Domain\Entity\Color;

interface ColorRepositoryInterface
{
    public function get(int $id): Color;

    public function list(): array;

    public function save(Color $object, bool $flush = false): void;

    public function remove(Color $object, bool $flush =false): void;
}
