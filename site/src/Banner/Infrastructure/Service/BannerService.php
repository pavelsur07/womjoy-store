<?php

declare(strict_types=1);

namespace App\Banner\Infrastructure\Service;

use App\Banner\Domain\Entity\BannerSetting;
use App\Banner\Infrastructure\Repository\BannerSettingRepository;

class BannerService
{
    public function __construct(private BannerSettingRepository $repo) {}

    public function getHeroSlider(): BannerSetting
    {
        $setting = $this->repo->findById(1);
        if ($setting === null) {
            $setting = $this->createSetting();
        }

        return $setting;
    }

    public function createSetting(): BannerSetting
    {
        $setting = new BannerSetting();
        $this->repo->save($setting, true);
        return $setting;
    }
}
