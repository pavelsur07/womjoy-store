<?php

declare(strict_types=1);

namespace App\Setting\Infrastructure\Service;

use App\Setting\Domain\Entity\Setting;
use App\Setting\Infrastructure\Repository\SettingRepository;

readonly class SettingService
{
    public function __construct(
        private SettingRepository $repo,
    ) {}

    public function get(): Setting
    {
        $setting = $this->repo->findById(1);
        if ($setting === null) {
            $setting = $this->create();
        }

        return $setting;
    }

    private function create(): Setting
    {
        $setting = new Setting();
        $this->repo->save($setting, true);
        return $setting;
    }
}
