<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Moysklad;

use App\Setting\Infrastructure\Service\SettingService;

readonly class MoyskladClientFactory
{
    public function __construct(
        private SettingService $settingService,
    ) {
    }

    public function __invoke(): MoyskladClient
    {
        $setting = $this->settingService->get();

        return new MoyskladClient(
            new MoyskladCredentials($setting->getMoysklad()->getToken()),
        );
    }
}
