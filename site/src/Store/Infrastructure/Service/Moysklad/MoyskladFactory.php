<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Moysklad;

use App\Setting\Infrastructure\Service\SettingService;
use App\Store\Infrastructure\Repository\OrderRepository;
use App\Store\Infrastructure\Repository\VariantRepository;

readonly class MoyskladFactory
{
    public function __construct(
        private MoyskladClient $moyskladClient,
        private OrderRepository $orderRepository,
        private VariantRepository $variantRepository,
        private SettingService $settingService,
    ) {
    }

    public function __invoke(): Moysklad
    {
        $setting = $this->settingService->get();

        return new Moysklad(
            $this->moyskladClient,
            $this->orderRepository,
            $this->variantRepository,
            new MoyskladOrganization($setting->getMoysklad()->getCompanyId()),
            new MoyskladStore($setting->getMoysklad()->getSkladId())
        );
    }
}
