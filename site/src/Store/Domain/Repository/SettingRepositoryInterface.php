<?php

declare(strict_types=1);

namespace App\Store\Domain\Repository;

use App\Store\Domain\Entity\Setting\Setting;

interface SettingRepositoryInterface
{
    public function find(): ?Setting;

    public function save(Setting $setting): void;
}
