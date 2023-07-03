<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Payment;

use App\Common\Infrastructure\Controller\BaseController;
use App\Common\Infrastructure\Doctrine\Flusher;
use App\Menu\Domain\Repository\MenuRepositoryInterface;
use App\Menu\Infrastructure\Service\MenuSettingService;
use App\Setting\Infrastructure\Service\SettingService;
use App\Store\Domain\Service\HomeService;
use App\Store\Infrastructure\Repository\OrderRepository;
use Omnipay\Omnipay;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route(path: '/cart/checkout/pay/stripe', name: 'store.checkout.pay.stripe')]
class StripeController extends BaseController
{
    public function __construct(
        MenuRepositoryInterface $menus,
        HomeService $homeService,
        UrlGeneratorInterface $generator,
        private readonly MenuSettingService $menuService,
        private readonly SettingService $settingService,
    ) {
        parent::__construct(
            $menus,
            $homeService,
            $generator,
            $this->menuService,
            $this->settingService,
        );
    }

    #[Route('/{reference}', name: '.pay')]
    public function payment(int $reference, OrderRepository $orders, Flusher $flusher): Response
    {
        $order = $orders->get($reference);
        $gateway = Omnipay::create('Stripe');
        $gateway->setApiKey('abc123');
        $formData = [];

        $response = $gateway->purchase(
            [
                'amount' => '10.00',
                'currency' => 'USD',
                'card' => $formData],
        )->send();
    }

    #[Route('/{reference}/success', name: '.success')]
    public function paymentSuccess(string $reference): void
    {
        // TODO Cart - clear
        // TODO Order status - pay
    }

    #[Route('/{reference}/fail', name: '.fail')]
    public function paymentFail(string $reference): void
    {
    }
}
