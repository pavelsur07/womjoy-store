<?php

declare(strict_types=1);

namespace App\Store\Domain\Repository;

use App\Store\Domain\Entity\Home\Home;

interface HomeSettingRepositoryInterface
{
    public function find(): ?Home;

    public function save(Home $home): void;
}
