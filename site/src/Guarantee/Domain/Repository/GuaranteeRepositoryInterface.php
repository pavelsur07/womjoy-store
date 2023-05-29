<?php

declare(strict_types=1);

namespace App\Guarantee\Domain\Repository;

use App\Guarantee\Domain\Entity\Guarantee;

interface GuaranteeRepositoryInterface
{
    public function get(int $id): Guarantee;

    public function list(): array;

    public function save(Guarantee $object, bool $flush = false): void;

    public function remove(Guarantee $object, bool $flush = false): void;
}
