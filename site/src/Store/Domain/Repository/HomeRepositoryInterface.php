<?php

declare(strict_types=1);

namespace App\Store\Domain\Repository;

use App\Store\Domain\Entity\Home\Home;

interface HomeRepositoryInterface
{
    public function find(): null|Home;

    public function save(Home $object, bool $flush = false): void;
}
