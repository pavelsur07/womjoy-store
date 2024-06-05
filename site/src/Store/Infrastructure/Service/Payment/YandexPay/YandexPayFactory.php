<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay;

use App\Setting\Infrastructure\Service\SettingService;
use App\Store\Infrastructure\Service\Payment\YandexPay\Client\ClientInterface;

readonly class YandexPayFactory
{
    public function __construct(
        private ClientInterface $client,
        private SettingService $settingService,
    ) {}

    public function __invoke(): YandexPay
    {
        $setting = $this->settingService->get();

        return new YandexPay(
            $this->client,
            $setting->getYandexPay()->getApiKey(),
            $setting->getYandexPay()->isSandbox()
        );
    }
}
