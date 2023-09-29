<?php

declare(strict_types=1);

namespace App\Menu\Infrastructure\Service;

use App\Menu\Domain\Entity\MenuSetting;
use App\Menu\Infrastructure\Repository\MenuSettingRepository;

readonly class MenuSettingService
{
    public function __construct(
        private MenuSettingRepository $repo,
    ) {}

    public function getSetting(): MenuSetting
    {
        $setting = $this->repo->findById(1);
        if ($setting === null) {
            $setting = $this->createSetting();
        }

        return $setting;
    }

    public function createSetting(): MenuSetting
    {
        $setting = new MenuSetting();
        $this->repo->save($setting, true);
        return $setting;
    }
}
