<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Api;

use App\Store\Domain\Entity\Order\ValueObject\OrderPayment;
use App\Store\Infrastructure\Request\Api\CheckoutDto;
use App\Store\Infrastructure\Service\Cart\CartService;
use App\Store\Infrastructure\Service\Order\OrderService;
use App\Store\Infrastructure\Service\Payment\PaymentProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/checkout', name: 'store.checkout.api')]
class OrderApiController extends AbstractController
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly OrderService $orderService,
        private readonly PaymentProvider $paymentProvider,
    ) {
    }

    #[Route(path: '/', name: '.index', methods: ['POST'], format: 'json')]
    public function index(
        #[MapRequestPayload]
        CheckoutDto $checkoutDto,
    ): Response {
        // Получаем текущую корзину
        $cart = $this->cartService->getCurrentCart(
            $userId = $this->getUser()?->getId()
        );

        // Создаём заказ.
        $order = $this->orderService->checkout($cart, $checkoutDto);

        // Получаем url перенаправления
        $redirectUrl = match ($order->getPayment()->getMethod()) {
            OrderPayment::PAYMENT_METHOD_COD => $this->generateUrl(
                route: 'store.checkout.finish',
                parameters: ['orderId' => $order->getId()->value()]
            ),
            OrderPayment::PAYMENT_METHOD_ONLINE => $this->generateUrl(
                route: $this->paymentProvider->getControllerRoute(),
                parameters: ['orderId' => $order->getId()->value()]
            ),
        };

        return $this->json(
            [
                'order_id' => $order->getId()->value(),
                'redirect_url' => $redirectUrl,
            ]
        );
    }
}
