<?php

declare(strict_types=1);

namespace App\Store\Domain\Service;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Setting\Setting;
use App\Store\Domain\Repository\SettingRepositoryInterface;

readonly class SettingService
{
    public function __construct(
        private SettingRepositoryInterface $repo,
        private Flusher $flusher,
    ) {
    }

    public function get(): Setting
    {
        $setting = $this->repo->find();

        if ($setting === null) {
            $setting = new Setting();
            $this->flusher->flush();
        }

        return $setting;
    }
}
