<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Unisender;

use App\Setting\Infrastructure\Service\SettingService;

readonly class UnisenderServiceBuilder
{
    public function __construct(
        private SettingService $service,
        private UnisenderService $client,
    ) {}

    public function build(): UnisenderService
    {
        $setting = $this->service->get();
        $this->client->setCredential(
            apiKey: $setting->getUnisender()->getKey(),
        );
        return $this->client;
    }
}
